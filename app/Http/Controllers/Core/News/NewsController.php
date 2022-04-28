<?php

namespace App\Http\Controllers\Core\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\SuggestedReads\SuggestedReadModel;

class NewsController extends Controller
{

	public $view = 'Core.News.backend.';
    public $frontend_view = 'Core.News.frontend.';
	private $storage_folder = 'news';

    /////////// Frontend ////////////////////
    public function getViewNews($id) {
        $data = NewsModel::where('id', $id)->firstOrFail();
        $data->counter += 1;
        $data->save();
        $view = (new NewsModel)->newsData($id);
        $view['tags'] = explode(',', $view['tags']);
        
        return view($this->frontend_view.'view-news')
                ->with('data', $data)
                ->with($view);
    }

    public function getNews() {
        $view = [];
        $view['latest_news'] = (new NewsModel)->getLatestNews();
        $view['top_news'] = (new NewsModel)->getTopNews();

        return view($this->frontend_view.'news')
                ->with($view);
    }

    /////////// Frontend ////////////////////

    public function getListView($news_type) {
        if($news_type != 'news' && $news_type != 'report'){
            return 404;
        }
        $data = NewsModel::orderBy('id', 'DESC')->where('news_type', $news_type)->paginate(20);
        $type = $news_type == 'news' ? ['news', 'report'] : ['report', 'news'];
        return view($this->view.'list')
                ->with('data', $data)
                ->with('type', $type);
    }

    public function getCreateView() {
        $core = $this->core.'Company\CompanyModel';
        $companies = $core::orderBy('company_name', 'ASC')->get();
        $type = request()->get('type');
        return view($this->view.'create')
                ->with('companies', $companies)->with('type', $type);
    }

    public function postCreateView() {
        $data = request()->all();

        $validator = \Validator::make($data['data'], (new NewsModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            if($data['data']['asset_type'] == 'image') {
                request()->file('data.asset')->store($this->storage_folder);
                $data['data']['asset'] = request()->file('data.asset')->hashName();
            }

            $news_id = NewsModel::create($data['data'])->id;

            $core = $this->core.'Tags\TagsModel';
            (new $core)->storeTagsAndCompany($data['tags']['tags'], $news_id);

            if(isset($data['company']['company_id'])) {
                foreach($data['company']['company_id'] as $company_id) {
                    CompanyNewsModel::create([
                        'company_id'    =>  $company_id,
                        'news_id'   =>  $news_id
                    ]);
                }
            }

            if(isset($data['is_suggested'])){
                SuggestedReadModel::create([
                    'category' => 'News',
                    'post_type' => 'App\Http\Controllers\Core\News\NewsModel',
                    'post_id' => $news_id,
                ]);
            }
        }

        \Session::flash('success-msg', 'News successfully created');

        return redirect()->back();
    }

    public function getEditView($id) {
        $news_type = request()->get('news_type');
        $type = $news_type == 'news' ? ['news', 'report'] : ['report', 'news'];

        $data = NewsModel::where('id', $id)->firstOrFail();
        $view = (new NewsModel)->newsData($id);

        return view($this->view.'edit')
                ->with('data', $data)
                ->with('type', $type)
                ->with($view);
    }

    public function postEditView($id) {
        $original_data = NewsModel::where('id', $id)->firstOrFail();
        $news_id = $id;
        $data = request()->all();
        $validator = \Validator::make($data['data'], (new NewsModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            if($data['data']['asset_type'] == 'image') {
                if(request()->hasFile('data.asset')) {
                    request()->file('data.asset')->store($this->storage_folder);
                    $data['data']['asset'] = request()->file('data.asset')->hashName();    
                } else {
                    $data['data']['asset'] = $original_data->asset;
                }
                
            }
            \DB::beginTransaction();

            if(isset($data['data']['is_suggested'])){
                SuggestedReadModel::firstOrCreate([
                    'category' => 'News',
                    'post_type' => 'App\Http\Controllers\Core\News\NewsModel',
                    'post_id' => $news_id,
                ]);
            }else{
                SuggestedReadModel::where('category', 'News')->where('post_id', $news_id)->delete();
                $data['data']['is_suggested'] = Null;
            }
            NewsModel::where('id', $id)->update($data['data']);
            $core = $this->core.'Tags\TagsModel';
            (new $core)->storeTagsAndCompany($data['tags']['tags'], $news_id, true);

            if(isset($data['company']['company_id'])) {
                CompanyNewsModel::where('news_id', $id)->delete();
                foreach($data['company']['company_id'] as $company_id) {
                    CompanyNewsModel::create([
                        'company_id'    =>  $company_id,
                        'news_id'   =>  $news_id
                    ]);
                }
            }

            \DB::commit();
            \Session::flash('success-msg', 'News successfully updated');
            return redirect()->back();
        }
    }

    public function postDeleteView($id) {
        
        $response = $this->apiDelete($id);

        if($response['status']) {
            \Session::flash('success-msg', $response['message']);
        } else {
            \Session::flash('error-msg', $response['message']);
            \Session::flash('friendly-error-msg', $response['friendly-error-msg']);
        }

        return redirect()->back();
    }

    public function postDeleteMultipleView() {
        $rids = request()->get('rid');
        $success = 0;
        $error = 0;
        if($rids) {
            foreach($rids as $r) {
                $response = $this->apiDelete($r);
                if($response['status']) {
                    $success++;
                } else {
                    $error++;
                }
            }
            if($success) {
                \Session::flash('success-msg', $success.' successfully deleted');
            }

            if($error) {
                \Session::flash('friendly-error-msg', $error.' could not be deleted');   
            }
        } else {
            \Session::flash('friendly-error-msg', 'No items selected');   
        }

        return redirect()->back();
    }

    public function apiDelete($id) {
        try {
            $data = NewsModel::where('id', $id)->firstOrFail();
            $suggested_read = SuggestedReadModel::where('category', 'News')->where('post_id', $id);

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();
            if (null !== $suggested_read->first()){
                $suggested_read->first()->delete();
            }
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'News could not be deleted'];
        }

        return ['status' => true, 'message' => 'News successfully deleted'];
        
    }

    public function postSetAsTopNewsView($id) {
        $msg = '';
        $data = NewsModel::where('id', $id)->firstOrFail();
        if($data->is_top_news == 'yes') {
            $msg = 'Unset As Top News';
            $data->is_top_news = 'no';
        } else {
            $msg = 'Set As Top News';
            $data->is_top_news = 'yes';
        }

        $data->save();
        session()->flash('success-msg', $msg);

        return redirect()->back();
    }

    public function postSetAsNewsBoardView($id) {
        $msg = '';
        $data = NewsModel::where('id', $id)->firstOrFail();
        
        $is_newsboard = $data->is_newsboard;

        if($is_newsboard == 'yes') {
            $msg = 'Unset from Newsboard';
            $data->is_newsboard = 'no';
            $data->save();
        } else {
            $msg = 'Set as Newsboard';
            NewsModel::where('id', '>', 0)->update([
                'is_newsboard' => 'no'
            ]);
            $data->is_newsboard = 'yes';
            $data->save();
        }

        $data->save();
        session()->flash('success-msg', $msg);

        return redirect()->back();   
    }
}