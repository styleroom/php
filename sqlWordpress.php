<?php
namespace pvc;

class WPsql {
    
    /**
     * 0.    получить все посты
     * 1.    получить одну статью по post_name
     * 2.    получить одну статью по ID
     * 3.    получить все рубрики
     * 4.    получить все непустые рубрики
     * 5.    получить статьи данной рубрики
     * 6.    получить все теги
     * 7.    получить статьи данного тега
     * 9.    получить всех непустых авторов
     * 10.   получить статьи данного автора
     * 11.   получить страницы архивов постов по годам
     * 12.   получить страницы архивов постов по годам-месяцам
     * 13.   получить архивы постов за данный год
     * 14.   получить архивы постов за данный месяц конкретного года
     * 15.   получить все родительские страницы
     * 16.   получить дочерние страницы данной страницы
     * 17.   получить ссылки на предыдущую и следующую статьи
     * 19.   получить ссылки на последние статьи
     * 20.   получить рубрику статьи
     * 21.   получить информацию по рубрике
     */
    
    /**
     * 21.
     * get category info
     */
    public $category_info = "
        SELECT 
            wp_terms.name, 
            wp_terms.slug, 
            wp_term_taxonomy.description 
        FROM wp_term_taxonomy 
        LEFT JOIN wp_terms USING (term_id) 
        WHERE wp_term_taxonomy.taxonomy = 'category' 
                AND wp_terms.slug = '#SLUG#'";    
    
    /**
     * 20.
     * get category for current posts
     * replace #LINK#
     */                
    public $category_for_post = "
        SELECT 
            wp_terms.name, 
            wp_terms.slug 
        FROM wp_posts 
        LEFT JOIN wp_term_relationships 
                    ON wp_term_relationships.object_id = wp_posts.ID 
        LEFT JOIN wp_term_taxonomy USING (term_taxonomy_id) 
        LEFT JOIN wp_terms USING (term_id) 
        WHERE wp_term_taxonomy.taxonomy = 'category' 
            AND wp_posts.post_name = '#LINK#'";
    
    /**
     * 0. 
     * get total posts
     */
    public $total_posts = "
        SELECT * 
        FROM wp_posts 
        WHERE post_status = 'publish' 
            AND post_type = 'post' 
        ORDER BY ID DESC";
    
    
    /**
     * 1.
     * get one post by link
     * replace #LINK#
     */
    public $post_by_link = "
        SELECT * 
        FROM wp_posts 
        WHERE post_name = '#LINK#' 
            AND post_status = 'publish'";
    
    /**
     * 2.
     * get one post by ID
     * replace #ID#
     */
    public $post_by_id = "
        SELECT * 
        FROM wp_posts 
        WHERE ID = '#ID#' 
            AND post_status = 'publish'";  
    
    /**
     * 3.
     * get all categories
     */    
    public $category_list_all = "
        SELECT 
            wp_term_taxonomy.count AS posts_count, 
            wp_terms.term_id, 
            wp_terms.name, 
            wp_terms.slug 
        FROM wp_term_taxonomy 
        LEFT JOIN wp_terms USING (term_id) 
        WHERE wp_term_taxonomy.taxonomy = 'category' 
        ORDER BY posts_count DESC";
    /**
     * 3.1
     * get all categories AS string
     */ 
    public $category_list_all_as_string = "
        SELECT 
            GROUP_CONCAT(
                DISTINCT wp_terms.name ORDER BY wp_terms.name ASC SEPARATOR ', '
                ) as list 
        FROM wp_term_taxonomy 
        LEFT JOIN wp_terms USING (term_id) 
        WHERE wp_term_taxonomy.taxonomy = 'category'";    
    
    /**
     * 4.
     * get all not empty categories
     */
    public $category_list_not_empty = "
        SELECT 
            wp_term_taxonomy.count AS posts_count, 
            wp_terms.term_id, 
            wp_terms.name, 
            wp_terms.slug 
        FROM wp_term_taxonomy 
        LEFT JOIN wp_terms USING (term_id) 
        WHERE wp_term_taxonomy.taxonomy = 'category' 
            AND wp_term_taxonomy.count > 0 
        ORDER BY posts_count DESC"; 
    
    /**
     * 5.
     * get all posts by current category
     * replace #SLUG#
     */
    public $category_posts = "
        SELECT 
            wp_terms.name, wp_terms.slug, 
            wp_term_taxonomy.term_id, wp_term_relationships.object_id, 
            wp_posts.post_title, wp_posts.post_name, wp_posts.post_content, 
            wp_posts.post_date, wp_users.user_nicename, 
            wp_term_taxonomy.description 
        FROM wp_terms 
        LEFT JOIN wp_term_taxonomy USING (term_id) 
        LEFT JOIN wp_term_relationships USING (term_taxonomy_id) 
        LEFT JOIN wp_posts ON wp_term_relationships.object_id = wp_posts.ID 
        LEFT JOIN wp_users ON wp_users.ID = wp_posts.post_author 
        WHERE wp_terms.slug = '#SLUG#' 
            AND wp_posts.post_status = 'publish' 
            AND wp_term_taxonomy.taxonomy = 'category' 
        ORDER BY post_date DESC";
    
    /**
     * 6.
     * get all tags
     */
    public $tag_list = "
        SELECT 
            wp_term_taxonomy.count AS posts_count, 
            wp_terms.term_id, 
            wp_terms.name, 
            wp_terms.slug 
        FROM wp_term_taxonomy 
        LEFT JOIN wp_terms USING (term_id) 
        WHERE wp_term_taxonomy.taxonomy = 'post_tag' 
        ORDER BY posts_count DESC";
    
    /**
     * 7.
     * get posts by tag slug
     * replace #SLUG#
     */
    public $tag_posts = "
        SELECT 
            wp_terms.name, wp_terms.slug, 
            wp_term_taxonomy.term_id, 
            wp_term_relationships.object_id, 
            wp_posts.post_title, wp_posts.post_name, 
            wp_posts.post_content 
        FROM wp_terms 
        LEFT JOIN wp_term_taxonomy USING (term_id) 
        LEFT JOIN wp_term_relationships USING (term_taxonomy_id) 
        LEFT JOIN wp_posts ON wp_term_relationships.object_id = wp_posts.ID 
        WHERE wp_terms.slug = '#SLUG#' 
            AND wp_term_taxonomy.taxonomy = 'post_tag' 
        ORDER BY wp_posts.post_date DESC";    
    
    
    /**
     * 9.
     * all posts by author
     * replace #AUTHOR#
     */
    public $author_not_empty = "
        SELECT 
            COUNT(*) as cou, 
            wp_users.user_nicename 
        FROM wp_posts 
        LEFT JOIN wp_users ON wp_users.ID = wp_posts.post_author 
        WHERE wp_posts.post_type = 'post' 
            AND wp_posts.post_status = 'publish' 
        GROUP BY wp_users.user_nicename 
        HAVING cou > 0 
        ORDER BY cou DESC";    
    
    /**
     * 10.
     * all posts by author
     * replace #AUTHOR#
     */
    public $author_posts = "
        SELECT 
            wp_posts.* 
        FROM wp_posts 
        LEFT JOIN wp_users ON wp_users.ID = wp_posts.post_author 
        WHERE wp_users.user_nicename = '#AUTHOR#' 
            AND wp_posts.post_type = 'post' 
            AND wp_posts.post_status = 'publish' 
        ORDER BY wp_posts.post_date DESC";    
    
    /**
     * 11.
     * archive pages by year
     */
    public $archive_pages_by_year = "
        SELECT DISTINCT 
            SUBSTRING(post_date, 1, 4) AS year 
        FROM wp_posts";
    
    /**
     * 12.
     * archive pages by year and month
     */
    public $archive_pages_by_year_month = "
        SELECT DISTINCT 
            SUBSTRING(post_date, 1, 7) AS year 
        FROM wp_posts";    
    

    /**
     * 13.
     * all post for year
     * replace #YEAR#
     */
    public $archive_posts_by_year = "
        SELECT * 
        FROM wp_posts 
        WHERE post_date LIKE '#YEAR#%' 
            AND post_type = 'post' 
            AND post_status = 'publish' 
        ORDER BY post_date ASC"; 
    
    /**
     * 14.
     * all post for year and month
     * replace #YEAR#
     * replace #MONTH#
     */    
    public $archive_posts_by_year_month = "
        SELECT * 
        FROM wp_posts 
        WHERE post_date LIKE '#YEAR#-#MONTH#%' 
            AND post_type = 'post' 
            AND post_status = 'publish' 
        ORDER BY post_date ASC"; 
    
    /**
     * 15. 
     * get all parent pages
     * that have daughter pages
     */
    public $page_parent = "
        SELECT 
            wpp.post_title, 
            wpp.post_name, 
            wpp.ID 
        FROM wp_posts wpp 
        WHERE wpp.ID IN 
            (
                SELECT post_parent 
                FROM wp_posts 
                WHERE post_type = 'page' 
                    AND post_parent != 0 
                GROUP BY post_parent 
                )
            AND wpp.post_parent = 0 
        GROUP BY wpp.post_title";

    
    /**
     * 16.
     * get all daughters pages
     * for current parent page
     * replace #LINK#
     */
    public $page_daughter = "
        SELECT * 
        FROM wp_posts 
        WHERE post_type = 'page' 
            AND post_status = 'publish' 
            AND post_parent = 
                (
                    SELECT ID 
                    FROM wp_posts 
                    WHERE post_name = '#LINK#' 
                )";    
    
    /**
     * 17.
     * get links to prev and next posts
     * replace #LINK#
     */
    public $prevnext_links = "
        (
            SELECT 
                post_title, post_name 
            FROM wp_posts 
            WHERE `post_status` = 'publish' 
                AND `post_date` < 
                    (
                        SELECT 
                            post_date 
                        FROM wp_posts 
                        WHERE post_name = '#LINK#'
                    ) 
            ORDER BY `post_date` DESC LIMIT 1
            ) 
            UNION 
            (
            SELECT post_title, post_name 
            FROM wp_posts 
            WHERE `post_status` = 'publish' 
                AND `post_date` > 
                    (
                        SELECT 
                            post_date 
                        FROM wp_posts 
                        WHERE post_name = '#LINK#'
                    ) 
            ORDER BY `post_date` ASC LIMIT 1
            )";    
    
    /**
     * 19.
     * get links to last posts
     */ 
    public $last_posts_link = "
        SELECT 
            wp_posts.post_title as title, 
            wp_posts.post_name as link 
        FROM wp_posts 
        WHERE post_type = 'post' 
            AND post_status = 'publish' 
        ORDER BY post_date DESC 
        LIMIT 5";
    
}
