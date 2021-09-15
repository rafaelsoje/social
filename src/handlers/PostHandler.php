<?php
/**
 * Created by PhpStorm.
 * User: Rafael
 * Date: 14/01/2021
 * Time: 22:37
 */

namespace src\handlers;

use src\models\Post;
use src\models\PostComment;
use src\models\PostLike;
use src\models\User;
use src\models\UserRelation;


class PostHandler
{
    public static $perPage = 10;

    public static function addPost($idUser, $type, $body )
    {
        $body = trim($body);
        if(!empty($idUser) && (!empty($body))){
            Post::insert([
                'id_user' => $idUser,
                'type' => $type,
                'created_at' => date('Y-m-d H:i:s'),
                'body' => $body
            ])->execute();
        }
    }

    public function _postListToObject($postList, $loggedUserId)
    {

        $posts = [];
        foreach ($postList as $postItem) {
            $newPost = new Post();
            $newPost->id = $postItem['id'];
            $newPost->type = $postItem['type'];
            $newPost->created_at = $postItem['created_at'];
            $newPost->body = $postItem['body'];
            $newPost->mine = false;

            if($postItem['id_user'] == $loggedUserId){
                $newPost->mine = true;
            }


//        preencher as  informações adicionais dos posts
            $newUser = User::select()->where('id', $postItem['id_user'])->one();

            $newPost->user = new User();
            $newPost->user->id = $newUser['id'];
            $newPost->user->name = $newUser['name'];
            $newPost->user->avatar = $newUser['avatar'];

//        prencher informações de likes
            $likes = PostLike::select()->where('id_post', $postItem['id'])->get();
            $newPost->likeCount = count($likes);
            $newPost->liked = self::isLiked($postItem['id'], $loggedUserId);
//        prencher informações de comentarios
            $newPost->comments = PostComment::select()->where('id_post', $postItem['id'])->get();

            foreach ($newPost->comments as $key => $comment){
                $newPost->comments[$key]['user'] = User::select()->where('id', $comment['id_user'])->one();
            }

            $posts[] = $newPost;
        }

        return $posts;

    }

    public static function isLiked($id, $loggedUserId)
    {
        $myLike = PostLike::select()
            ->where('id_post', $id)
            ->where('id_user', $loggedUserId)
            ->get();

        if(count($myLike) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteLike($id, $loggedUser)
    {
        PostLike::delete()
            ->where('id_post', $id)
            ->where('id_user', $loggedUser)
        ->execute();
    }

    public static function addLike($id, $loggedUser)
    {
        PostLike::insert([
            'id_post' => $id,
            'id_User' => $loggedUser,
            'created_at' => date('Y-m-d H:i:s')
        ])->execute();
    }

    public static function addComment($id, $txt, $loggedUser)
    {
        PostComment::insert([
            'id_post' => $id,
            'id_user' => $loggedUser,
            'created_at' => date('Y-m-d H:i:s'),
            'body' => $txt
        ])->execute();
    }

    public static function getUserFeed($idUser, $page, $loggedUserId)
    {
        $postList = Post::select()
            ->where('id_user', $idUser)
            ->orderBy('created_at', 'desc')
            ->page($page, self::$perPage)
            ->get();

        $total = Post::select()
            ->where('id_user', $idUser)
            ->count();

        $pageCount = ceil($total / self::$perPage);

//        tranformar o resultado em objeto do model
        $posts = self::_postListToObject($postList, $loggedUserId);

        //        retornar o resultado
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];
    }

    public static function getHomeFeed($idUser, $page)
    {
//        $perPage = 2;
//        lista de usuarios que eu sigo
        $userList = UserRelation::select()->where('user_from', $idUser)->get();
        $users = [];
        foreach ($userList as $userItem){
            $users[] = $userItem['user_to'];
        }

        $users[] = $idUser;

//        pegas os posts ordenado por data
        $postList = Post::select()
            ->where('id_user', 'in', $users)
            ->orderBy('created_at', 'desc')
            ->page($page, self::$perPage)
        ->get();

        $total = Post::select()
            ->where('id_user', 'in', $users)
        ->count();

        $pageCount = ceil($total / self::$perPage);

//        tranformar o resultado em objeto do model
        $posts = self::_postListToObject($postList, $idUser);
//        retornar o resultado
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];
    }

    public function getPhotosFrom($idUser)
    {
        $photosData = Post::select()
            ->where('id_user', $idUser)
            ->where('type', 'photo')
        ->get();

        $photos = [];

        foreach ($photosData as $photo){
            $newPost = new Post();
            $newPost->id = $photo['id'];
            $newPost->type = $photo['type'];
            $newPost->created_at = $photo['created_at'];
            $newPost->body = $photo['body'];

            $photos[] = $newPost;
        }

        return $photos;
    }

    public static function delete($id, $loggedUserId)
    {
        $post = Post::select()
            ->where('id', $id)
            ->where('id_user', $loggedUserId)
        ->get();

        if(count($post) > 0){
            $post = $post[0];

            PostLike::delete()->where('id_post', $id)->execute();
            PostComment::delete()->where('id_post', $id)->execute();

            if($post['type'] === 'photo'){
                $img = 'media/uploads/'. $post['body'];
                var_dump($img);
                if(file_exists($img)){
                    unlink($img);
                }
            }

            Post::delete()->where('id', $id)->execute();
        }
    }

}