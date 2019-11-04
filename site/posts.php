<?php

require_once("../config/output.php");
require_once("../config/func_posts.php");

session_start();

output_head("Posts");

output_header();

print("<h1>Posts</h1>");

if (isset($_GET['page']))
    $index = intval($_GET['page']);
else
    $index = 1;

$posts = get_posts($index);
if ($posts)
{
    foreach ($posts as $post)
    {
        print("<div class='post'>");
        print("  <img class='postimg' src=\"/postimages/" . $post['post_id'] . ".png\">");
        print("<div class='postusername'>Posted by " . $post['username'] . "</div>");
        print("<div class='postdate'>Posted on " . $post['post_date'] . "</div>");
        print("</div>");
    }
}

print("<br /><br />");
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
