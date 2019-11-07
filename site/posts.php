<?php

require_once("../config/output.php");
require_once("../config/func_posts.php");
require_once("../config/func_comments.php");

session_start();

output_head("Posts");

output_header();

print("<h1>Posts</h1><div class='posts'>");

if (isset($_GET['page']))
    $index = intval($_GET['page']);
else
    $index = 1;

function class_if_liked(int $postid)
{
    if (!isset($_SESSION['username']))
        print(" disabled");
    elseif (is_liked($postid, $_SESSION['username']))
        print(" likeDbutton");
}

function like_value(int $postid)
{
    if (!isset($_SESSION['username']) || !is_liked($postid, $_SESSION['username']))
        return ("true");
    else
        return ("false");
}

$posts = get_posts($index);
if ($posts)
{
    foreach ($posts as $post)
    {
        print("<div class='post'>");
        print("  <img class='postimg' src=\"/postimages/" . $post['post_id'] . ".png\">");

        print("  <form method='POST' action='api/posts'>");
        print("    <input type='hidden' name='postid' value=\"" . $post['post_id'] . "\" />");
        print("    <input type='hidden' name='like' value=\"" . like_value($post['post_id']) . "\" />");
        print("    <div class='postdetails'>");
            print($post['username'] . " | " . $post['post_date'] . " | " . likes_count($post['post_id']) . " ");
            print("<button type='submit' name='action' value='like' class='likebutton");
                class_if_liked($post['post_id']);
                print("'>♥︎</button>");
        print("    </div>");
        if (isset($_SESSION['username']) && $post['username'] === $_SESSION['username'])
            print("<button type='submit' name='action' value='delete' class='deletepost'>Delete</button>");
        print("  </form>");

        print("  <div class='comments'>");
        $comments = get_comments($post['post_id']);
        if ($comments)
        {
            foreach ($comments as $comment)
            {
                print("<div class='commentdetails'>");
                print($comment['username'] . " | " . $comment['post_date'] . "");
                print("</div><div class='commenttext'>");
                print($comment['text']);
                print("</div>");
            }
        }
        print("    <form method='POST' action='api/comments'>");
        print("      <input type='hidden' name='postid' value=\"" . $post['post_id'] . "\" />");
        print("      <textarea name='posttext' rows='2' cols='48' placeholder='Post a comment...' class='commentinput'></textarea>");
        print("      <br /><button type='submit' name='action' value='add' class='postcomment'>Post</button>");
        print("    </form>");
        print("  </div>");
        print("</div>");
    }
}

print("</div><br /><br />");
print("<div class='pages'>");

$endval = post_page_count();
for ($i = 1; $i < $endval + 1; $i++)
{
    print("<a class='pageno' href=\"/posts?page=" . $i . "\">");
    print("<div class='pageno");
    if ($i == $index)
        print(" current");
    print("'>" . $i . "</div></a>");
}

print("</div>");

output_footer();

output_end();

?>
