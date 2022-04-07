<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;

class NftController extends Controller
{
    public function makeNft(Request $request){
        $request->validate([
            'postId' => 'required'
        ], [
            'postId.required' => 'Контентийг ID-г оруулна уу'
        ]);

        $userId = Auth::user()->id;

        $postId = $request->input('postId');
        $post = Post::where('user_id', $userId )->where('id', $postId)->first();

        if(!$post){
            return $this->not();
        }

        $wallet = UserWallet::where('user_id', $userId)->first();

        if(!$wallet){
            return $this->not(['message'=>'ямар нэгэн Wallet холбооргүй байна']);
        }

        $contractAddress =env('WEB3_CONTRACT_ADDRESS');
        $abi = env('WEB3_ABI');
        $web3Account = env('WEB3_ACCOUNT');
        $web3AccountPass = env('WEB3_ACCOUNT_PASS');
        // $web3 = new Web3('http://localhost:8545');
        $web3 = new Web3(new HttpProvider(new HttpRequestManager('http://localhost:8545', 10)));

        $d = '';

        $functionName = "awardItem";
        $toAddress = $wallet->wallet_address;
        $tokenId = $postId;
        $tokeURL = $post->content_image;
        $web3->personal->unlockAccount( $web3Account, $web3AccountPass, 10, function($error, $data)use(&$d){
            $d = $data;
        });

        $contract = new Contract($web3->provider, $abi);

        $contract->at($contractAddress)->send($functionName, $toAddress, $tokenId, $tokeURL,['from'=>$web3Account], function($error, $data) use(&$d){
            $d = $data;
        });

        $post->is_nft = ParameterController::$POST_NFT;
        $post->nft_id = $d;
        $post->update();
        
        // $accounts = $web3->eth()->getBalance('0x938a5D3065993aB0f71cAC81B350a6350D3f9d8e')->toEth();
        // $accounts = $web3->eth()->accounts();

        // $data = $web3->sha3('string');

        // $web3->personal->listAccounts(function ($err, $version) {
        //     if ($err !== null) {
        //         // do something
        //         return;
        //     }
        //     if (isset($version)) {
        //         // echo 'Client version: ' . $version;
        //         $ss = $version;
        //     }
        // });

        // $version = $web3->clientVersion();
        // $accounts = $web3->eth()->accounts();


        return ['new'=>$d];

        $postId = $request->input("postId");
        $tokenId = $request->input("tokenId");

        $post = Post::find($postId);

        if(!$post){
            return $this->not(['message'=>'Контент олдсонгүй']);
        }

        $post->nft_id = $tokenId;
        $post->is_nft = ParameterController::$POST_NFT;
        $post->update();

        return $this->suc([]);
    }
}
