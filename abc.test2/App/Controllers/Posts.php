<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Post;

class Posts extends \Core\Controller
{

    public function indexAction()
    {
        //echo 'Hello from the index action in the Posts controller!';
        $posts = Post::getAll();
        View::renderTemplate('Posts/index.html', [
            'posts' => $posts
        ]);
    }

    public function addNewAction()
    {
        // $posts = Post::addPost();
        View::renderTemplate('Posts/add.html');
    }

    public function insertAction()
    {

        $table = 'posts';

        $title = $_POST['title'];
        $content = $_POST['content'];

        $photo = $_FILES['photo']['name'];
        $tmp_image = $_FILES['photo']['tmp_name'];
        $div =  explode('.', $photo);
        $file_ext = strtolower(end($div));
        $unique_image = $div[0] . time() . '.' . $file_ext;

        $path_uploads = "/var/www/abc.test/public/images/" . $unique_image;

        $data = array(
            'title' => $title,
            'content' => $content,
            'photo' => $unique_image
        );



        $result = Post::addPost($table, $data);

        if ($result == 1) {
            move_uploaded_file($tmp_image, $path_uploads);
            $mess['msg'] = 'Thêm thành công';
            return $this->redirect('http://abc.test/?posts/index');
        } else {
            $mess['msg'] = 'Thêm thất bại';
            return $this->redirect('http://abc.test/?posts/index');
        }
    }

    public function getid($url)
    {
        $id = preg_replace('/[^0-9]/', '', $url);
        return $id;
    }

    public function editAction()
    {
        $url = $_SERVER['QUERY_STRING'];
        $id = $this->getid($url);

        // $table = 'mvc';

        $posts = Post::editPost($id);

        View::renderTemplate('Posts/edit.html', [
            'posts' => $posts
        ]);
    }

    public function updateAction()
    {
        $url = $_SERVER['QUERY_STRING'];
        $id = $this->getid($url);

        $table = "posts";
        $cond = "posts.id ='$id'";

        $title = $_POST['title'];
        $content = $_POST['content'];

        $photo = $_FILES['photo']['name'];
        $tmp_image = $_FILES['photo']['tmp_name'];
        $div =  explode('.', $photo);
        $file_ext = strtolower(end($div));
        $unique_image = $div[0] . time() . '.' . $file_ext;

        $path_uploads = "/var/www/abc.test/public/images/" . $unique_image;

        if ($photo) {
            $data['editPost'] = Post::editPost($cond);
            print_r($data);
            // die();
            foreach ($data['editPost'] as $key => $value) {
                if ($value['photo']) {
                    echo $value['photo'];
                    $path_unlink = "/var/www/abc.test/public/images/";
                    unlink($path_unlink . $value['photo']);
                }
            }
            $data = array(
                'title' => $title,
                'content' => $content,
                'photo'      => $unique_image
            );
            move_uploaded_file($tmp_image, $path_uploads);
        } else {
            $data = array(
                'title' => $title,
                'content' => $content
                //    'image'      => $unique_image
            );
        }
        $result = Post::updatePost($table, $data, $cond);

        // if ($result == 1) {
        //     $mess['msg'] = 'Thao tác thành công';
        //     return $this->redirect('http://abc.test/?posts/index');
        // } else {
        //     $mess['msg'] = 'Thao tác thất bại';
        //     return $this->redirect('http://abc.test/?posts/index');
        // }

    }

    public function deleteAction()
    {

        $url = $_SERVER['QUERY_STRING'];
        $id = $this->getid($url);

        $table = 'posts';
        $sucess = Post::editPost($table, $id);
        if ($sucess) {
            return $this->redirect('http://abc.test/?posts/index');
        }
        return false;
    }

    public function redirect($url)
    {
?>
        <script>
            $url = '<?= $url ?>'
            window.location.href = $url;
        </script>

<?php

    }
}
