<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

class FeedController extends Controller
{
    // fetchXml is used to proccess the post request with the URL, page number (default value = 1) and products per page (default value = 100)
    // incase of successfull url, an object is sent to index.php view with xml reader, page number, feed url and products per page
    // incase of error while trying to parse url, exception is passed to error.php and give user option to re-try and submit trader tracker products xml products
	public function fetchXml(Request $request) {
		try
		{
			$url = $request->feedUrl;
			$productsPerPage = $request->productsPerPage;
			$pageNumber = $request->pageNumber;
			$reader = new \XMLReader();
			$reader->open($url);
			$data = array('reader' => $reader, 'n' => $pageNumber, 'url' => $url, 'productsPerPage' => $productsPerPage); 
			return view('index')->with('data', $data);
		}
		catch(\Exception $exception)
		{
			return view('errorPage')->with('exception', $exception);
		}
		catch(TokenMismatchException $exception)
		{
			var_dump($exception);
			//return view('errorPage')->with('exception', $exception);
		}
	}
}
