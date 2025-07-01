<?php
/**
* Comments Helper Class
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.3
*
* Sample Usage
*
* Getting Total Comments
*  echo CommentsHelper::getComments($row->id, 'BugTracker');
*  echo CommentsHelper::getTotalCommentsCount($row->id, 'BugTracker');
*  echo CommentsHelper::getTotalComments($row->id, 'BugTracker');
*
* Displaying Comments
*  echo CommentsHelper::displayComments($get_var_2, 'BugTracker');
*/

namespace Helpers;

use Helpers\{Database,Auth,Request,Form,CurrentUserData,TimeDiff,BBCode,Popups,Mail};

class CommentsHelper
{

  /**
   * Ready the database for use in this helper.
   *
   * @var string
   */
  private static $db;

  /**
 * getComments
 *
 * gets comment count
 *
 * @param int $com_id (ID of post where comment is)
 * @param string $com_location (Section of site where comment is)
 * @param int $com_sec_id (ID of secondary post)
 * @param string $display_type
 *
 * @return string returns comment data
 */
  public static function getComments($com_id = null, $com_location = null, $display_type = "btn"){
    // Get comment count from db
    // Check to see if this is a comment for a secondary post
    // Comment is for secondary post
    self::$db = Database::get();
    $com_count = self::$db->selectCount(PREFIX."helper_comments", array('com_id' => $com_id,'com_location' => $com_location));

    if($display_type == "hideZero"){
      if($com_count > 0){
        // Get time ago for last comment
        $latestComment = self::$db->select("SELECT timestamp FROM ".PREFIX."helper_comments WHERE com_id = :com_id AND com_location = :com_location ORDER BY id DESC LIMIT 1", array('com_id' => $com_id, 'com_location' => $com_location));
        $latestCommentTimeAgo = (!empty($latestComment[0]->timestamp)) ? "Latest comment " . TimeDiff::dateDiff("now", $latestComment[0]->timestamp, 1) . " ago " : "";
        return "<i class=\"fa-solid fa-message\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"$latestCommentTimeAgo\"><span class='badge rounded-pill bg-secondary'>$com_count</span></i>";
      }
    }else if($display_type == "btn"){
      return "<i class=\"fa-solid fa-message\"></i><span class='badge rounded-pill bg-secondary'>$com_count</span>";
    }else if($display_type = "num"){
      return $com_count;
    }
  }

  /**
 * displayCommentsButton
 *
 * display comments button
 * update/add comments type
 *
 * @return string returns comment button data
 */
  public static function displayComments($com_id = null, $com_location = null, $com_url = null, $com_sec_id = "0", $com_secondary = null, $page = "view_comments"){
    // Get the Current User's ID if logged in
    $auth = new Auth();
    if ($auth->isLogged()) {
        $com_owner_userid = $auth->currentSessionInfo()['uid'];
    }else{
        $com_owner_userid = null;
    }
    // Check to see if basic settings are being used.  No Specific data.
    // Default to use url to get comments
    if(empty($com_location)){
      $com_location = rtrim($_SERVER['REQUEST_URI'], '/');
    }
    // Check to see if the com_url is set
    if(empty($com_url)){
      $com_url = ltrim($_SERVER['REQUEST_URI'], '/')."/";
    }
    // Set the com_id to 0 if not set already
    if(empty($com_id)){
      $com_id = "0";
    }
    // Check to see if current user has already commented page
    self::$db = Database::get();

    // Comment is for main post
    $com_data = self::$db->select("SELECT * FROM ".PREFIX."helper_comments WHERE com_id = :com_id AND com_location = :com_location ORDER BY id ASC ",
                                    array(':com_id' => $com_id, ':com_location' => $com_location));
    // Get count to see if user has already submitted a comment
    $com_count = count($com_data);

    // Check to see if not view_comments and greater than 3
    $get_total_comments = self::getTotalCommentsCount($com_id, $com_location);
    if($page !== 'view_comments' && $get_total_comments > 3){
      $view_comments_link = "<a href='".SITE_URL."Comments/".$com_location."/".$com_id."/'>View All Comments</a>";
    }else{
      $view_comments_link = "";
    }

    // Clean url by removing the anchor
    $clean_com_url = substr($com_url, 0, strrpos( $com_url, '/'));
    // Setup Current Comments display
    $button_title = "Post Comment";
    $button_color = "success";
    $button_img_size = "col-2";
    $table_style = "";
    $display_comments = "";

    foreach ($com_data as $com) {
      // Setup Comment Edit Form
      $edit_comment = Request::post('edit_comment');
      $post_edit_id = Request::post('edit_id');
      $clean_com_url = substr($com_url, 0, strrpos( $com_url, '/'));
      $com_edit_display = Form::open(array('method' => 'post', 'style' => 'display:inline-grid; width:100%'));
        // Display Comment Edit Button
        $com_edit_display .= "<div class='input-group comment-box'>";
        $com_edit_display .= " <input type='hidden' name='update_comment' value='true' /> ";
        $com_edit_display .= " <input type='hidden' name='edit_id' value='$com->id' /> ";
        $com_edit_display .= " <input type='hidden' name='com_id' value='$com_id' /> ";
        $com_edit_display .= " <input type='hidden' name='com_sec_id' value='$com_sec_id' /> ";
        $com_edit_display .= " <input type='hidden' name='com_location' value='$com_location' /> ";
        $com_edit_display .= " <input type='hidden' name='com_owner_userid' value='$com_owner_userid' /> ";
        $com_edit_display .= Form::textBox(array('type' => 'text', 'id' => 'com_content', 'name' => 'com_content', 'class' => 'form-control', 'value' => $com->com_content, 'placeholder' => 'Comment', 'rows' => '1'));
        $com_edit_display .= "<div class='input-group-append'>";
        $com_edit_display .= " <button type='submit' class='btn btn-$button_color btn-sm float-right' value='Comment' name='comment'> Update Comment </button> ";
        $com_edit_display .= "</div></div>";
      // Close the Comment Edit Button Form
      $com_edit_display .= Form::close();
      // Setup Comment Delete Form
      $com_delete_button_display = Form::open(array('method' => 'post', 'style' => 'display:inline'));
        // Display Comment Delete Button
        $com_delete_button_display .= "<input type='hidden' name='delete_comment' value='true' />";
        $com_delete_button_display .= "<input type='hidden' name='com_id' value='$com->id' />";
        $com_delete_button_display .= "<input type='hidden' name='com_location' value='$com_location' />";
        $com_delete_button_display .= "<input type='hidden' name='com_owner_userid' value='$com_owner_userid' />";
        $com_delete_button_display .= "<button type='submit' class='btn btn-danger' value='submit' name='submit'>Delete</button>";
      // Close the Comment Delete Button Form
      $com_delete_button_display .= Form::close();
      $com_delete_model_dispaly = "
          <button type=\"button\" class=\"btn btn-sm btn-link trigger-btn\" data-bs-toggle=\"modal\" data-bs-target=\"#DeleteModal".$com->id."\">Delete</button>
        <div class='modal fade' id='DeleteModal".$com->id."' tabindex='-1' role='dialog' aria-labelledby='DeleteLabel' aria-hidden='true'>
          <div class='modal-dialog' role='document'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h5 class='modal-title' id='DeleteLabel'>Delete Comment</h5>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
              </div>
              <div class='modal-body'>
                Do you want to delete comment?
              </div>
              <div class='modal-footer'>
              <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Cancel</button>
                $com_delete_button_display
              </div>
            </div>
          </div>
        </div>
      ";
      // Setup Comment Delete Form
      $com_edit_button_display = Form::open(array('method' => 'post', 'style' => 'display:inline', 'action' => '#viewcom'.$com->id));
        // Display Comment Delete Button
        $com_edit_button_display .= "<input type='hidden' name='edit_comment' value='true' />";
        $com_edit_button_display .= "<input type='hidden' name='edit_id' value='$com->id' />";
        $com_edit_button_display .= "<input type='hidden' name='com_location' value='$com_location' />";
        $com_edit_button_display .= "<input type='hidden' name='com_owner_userid' value='$com_owner_userid' />";
        $com_edit_button_display .= "<button type='submit' class='btn btn-sm btn-link' value='submit' name='submit'>Edit</button>";
      // Close the Comment Delete Button Form
      $com_edit_button_display .= Form::close();
      // Get user data
      $com_userName = CurrentUserData::getUserFullName($com->com_owner_userid);
      //$com_userImage = CurrentUserData::getUserImage($com->com_owner_userid);
      $com_timeago = TimeDiff::dateDiff("now", "$com->timestamp", 1) . " ago ";

      // Display comment
      $display_comments .= "<div class='card mb-1'>";
        $display_comments .= "<div class='card-header p-1'>";
          $display_comments .= "<div class='row'>";
            $display_comments .= "<div class='col-md-6 text-start'>";
              $display_comments .= "<a class='anchor' name='viewcom$com->id'></a>";
              $display_comments .= "<i class=\"fa-solid fa-user-tie\"></i> ";
              $display_comments .= "<b><strong>$com_userName</strong> <font class='text-muted' size='1'> $com_timeago</font></b>";
            $display_comments .= "</div>";
            $display_comments .= "<div class='col-md-6 text-end'>";
              if($com->com_owner_userid == $com_owner_userid){ $display_comments .= $com_edit_button_display; }
              if($com->com_owner_userid == $com_owner_userid){ $display_comments .= $com_delete_model_dispaly; }
            $display_comments .= "</div>";
          $display_comments .= "</div>";
        $display_comments .= "</div>";
        $display_comments .= "<div class='card-body p-1'>";
          if($edit_comment == 'true' && $post_edit_id == $com->id && $com->com_owner_userid == $com_owner_userid){
            $display_comments .= $com_edit_display;
          }else{
            $comment_content = BBCode::getHtml($com->com_content);
            $display_comments .= "<pre class='forum'>".$comment_content."</pre>";
          }
        $display_comments .= "</div>";
      $display_comments .= "</div>";
    }

    $display_comments .= "";

      // Setup Comment Button Form
      $com_button_display = Form::open(array('method' => 'post', 'style' => 'display:inline-grid; width:100%'));
        // Display Comment Button
        $com_button_display .= "<div class='card mb-1'>";
          $com_button_display .= "<div class='card-header p-1'>";
            $com_button_display .= "<div class='input-group'>";
            $com_button_display .= " <input type='hidden' name='submit_comment' value='true' /> ";
            $com_button_display .= " <input type='hidden' name='com_id' value='$com_id' /> ";
            $com_button_display .= " <input type='hidden' name='com_sec_id' value='$com_sec_id' /> ";
            $com_button_display .= " <input type='hidden' name='com_location' value='$com_location' /> ";
            $com_button_display .= " <input type='hidden' name='com_owner_userid' value='$com_owner_userid' /> ";
            $com_button_display .= Form::textBox(array('type' => 'text', 'id' => 'com_content', 'name' => 'com_content', 'class' => 'form-control', 'value' => '', 'placeholder' => 'Comment', 'rows' => '1'));
            $com_button_display .= " <button type='submit' class='btn btn-$button_color btn-sm float-right' value='Comment' name='comment'> $button_title </button> ";
            $com_button_display .= "</div>";
          $com_button_display .= "</div>";
        $com_button_display .= "</div>";
      // Close the Comment Button Form
      $com_button_display .= Form::close();

      if(!empty($view_comments_link)){ $display_comments .= $view_comments_link; }

      // Check to see if user is submitting a new comment
      $submit_comment = Request::post('submit_comment');
      $update_comment = Request::post('update_comment');
      $delete_comment = Request::post('delete_comment');
      $post_com_id = Request::post('com_id');
      $post_com_location = Request::post('com_location');
      $post_com_owner_userid = Request::post('com_owner_userid');
      $post_com_sec_id = Request::post('com_sec_id');
      $post_com_content = Request::post('com_content');
      if($submit_comment == "true" && $post_com_id == $com_id && $post_com_location == $com_location){
        self::addComment($post_com_id, $post_com_location, $post_com_owner_userid, $post_com_sec_id, $com_url, $post_com_content);
      }else if($update_comment == "true" && $post_com_id == $com_id && $post_com_location == $com_location){
        self::updateComment($post_edit_id, $post_com_location, $post_com_owner_userid, $com_url, $post_com_content);
      }else if($delete_comment == "true" && $post_com_owner_userid == $com_owner_userid && $post_com_location == $com_location){
        self::removeComment($post_com_id, $post_com_location, $post_com_owner_userid, $com_url);
      }
      // Check to see if any comments
      if(empty($com_data)){
        $display_comments = ' ';
      }
      // Ouput the comment/uncomment button
      // Make sure that there is a user logged in
      if($com_owner_userid != null){
        return $display_comments.$com_button_display;
      }else{
        return $display_comments;
      }
  }

  /**
 * addComment
 *
 * add comment to database
 *
 * @param int $com_id (ID of post where comment is)
 * @param string $com_location (Section of site where comment is)
 * @param int $com_owner_userid (ID of user commenting)
 * @param int $com_sec_id (ID of secondary post)
 * @param string $com_url (redirect url)
 * @param string $com_content (Comment content)
 *
 */
  public static function addComment($com_id = null, $com_location = null, $com_owner_userid = null, $com_sec_id = "0", $com_url = null, $com_content = null){
    /** Check to make sure Comment has content in it **/
    if(!empty($com_content)){
      /** Insert New Comment Into Database **/
      self::$db = Database::get();
      $com_add_data = self::$db->insert(
        PREFIX.'helper_comments',
         array('com_id' => $com_id,
               'com_location' => $com_location,
               'com_owner_userid' => $com_owner_userid,
               'com_sec_id' => $com_sec_id,
               'com_content' => $com_content
             ));
      if($com_add_data > 0){
        /** Clean url by removing the anchor **/
        $clean_com_url = substr($com_url, 0, strrpos( $com_url, '/'));
        /** Send all users in this comment chain an email notification **/
        $users_in_comments = self::getInCommentsUsers($com_id, $com_location, $com_owner_userid);
        /** EMAIL MESSAGE USING PHPMAILER **/
        if(!empty($users_in_comments)){
          $user_commenter = CurrentUserData::getUserFullName($com_owner_userid);
          $com_location = str_replace("Sec", "", $com_location);
          $mail = new Mail();
          $mail->setFrom(SITE_EMAIL, EMAIL_FROM_NAME);
          foreach($users_in_comments as $row){
            $mail->addBCC($row);
          }
          $mail_subject = SITE_TITLE . " - New Comment by ".$user_commenter;
          $mail->subject($mail_subject);
          $body = "<b>".SITE_TITLE." - Comment Notification </b>
                                <hr/>
                                <b>New Comment by ".$user_commenter." to ".$com_location." on ".SITE_TITLE."</b>
                                <hr/>
                                <b>Comment</b>:<br/>
                                ".$com_content."
                                <hr/>";
          $body .= "<a href=\"".SITE_URL.$clean_com_url."/#viewcom".$com_add_data."\">View Comment</a>";
          $mail->body($body);
          $mail->send();
        }
        /** Success **/
        Popups::pushSuccess('You Have Successfully Submitted a Comment', $clean_com_url."/#viewcom$com_add_data");
      }else{
        Popups::pushError('There Was an Error Submitting Comment', $com_url);
      }
    }else{
      Popups::pushError('Comment was blank.  Please try again.', $com_url);
    }
  }

  /**
 * removeComment
 *
 * delete comment from database
 *
 * @param int $com_id (ID of post where comment is)
 * @param string $com_location (Section of site where comment is)
 * @param int $com_owner_userid (ID of user commenting)
 * @param int $com_sec_id (ID of secondary post)
 * @param string $com_url (redirect url)
 *
 */
  public static function removeComment($com_id = null, $com_location = null, $com_owner_userid = null, $com_url = null){
      // Insert New Comment Into Database
      self::$db = Database::get();
      $com_remove_data = self::$db->delete(
        PREFIX.'helper_comments',
          array('id' => $com_id,
                'com_location' => $com_location,
                'com_owner_userid' => $com_owner_userid
              ));
      if($com_remove_data > 0){
        // Success
        // Clean url by removing the anchor
        $clean_com_url = substr($com_url, 0, strrpos( $com_url, '/'));
        Popups::pushSuccess('You Have Successfully Deleted a Comment', $clean_com_url);
      }else{
        Popups::pushError('There Was an Error Deleting Comment', $com_url);
      }
  }

  /**
 * updateComment
 *
 * update comment in database
 *
 * @param int $com_id (ID of post where comment is)
 * @param string $com_location (Section of site where comment is)
 * @param int $com_owner_userid (ID of user commenting)
 * @param int $com_sec_id (ID of secondary post)
 * @param string $com_content
 * @param redirect $com_url (redirect url)
 *
 */
  public static function updateComment($com_id = null, $com_location = null, $com_owner_userid = null, $com_url = null, $com_content = null){
      // Insert New Comment Into Database
      self::$db = Database::get();
      $com_remove_data = self::$db->update(
        PREFIX.'helper_comments',
          array('com_content' => $com_content),
          array('id' => $com_id,
                'com_location' => $com_location,
                'com_owner_userid' => $com_owner_userid
              ));
      if($com_remove_data > 0){
        // Success
        // Clean url by removing the anchor
        $clean_com_url = substr($com_url, 0, strrpos( $com_url, '/'));
        Popups::pushSuccess('You Have Successfully Updated a Comment', $clean_com_url);
      }else{
        Popups::pushError('There Was an Error Updating Comment', $com_url);
      }
  }

  /**
 * getTotalComments
 *
 * gets comment count for all comments releated to com_id
 *
 * @param int $com_id (ID of post where comments are)
 * @param string $com_location (Section of site where comments are)
 * @param string $com_sec_location (Related location where comments are)
 *
 * @return string returns comment data
 */
  public static function getTotalComments($com_id = null, $com_location = null, $com_sec_location = null){
    self::$db = Database::get();
    $com_count = self::$db->select("
        SELECT
          *
        FROM
          ".PREFIX."helper_comments
        WHERE
          (com_id = :com_id)
        AND
          (com_location = :com_location OR com_location = :com_location2)
        ",
      array(':com_id' => $com_id,
            ':com_location' => $com_location,
            ':com_location2' => "Sec".$com_location
          ));
    $com_total = count($com_count);
    $com_display = " <div class='btn btn-success btn-sm'>Comments <span class='badge badge-light'>$com_total</span></div> ";
    return $com_display;
  }

  /**
 * getTotalComments
 *
 * gets comment count for all comments releated to com_id
 *
 * @param int $com_id (ID of post where comments are)
 * @param string $com_location (Section of site where comments are)
 * @param string $com_sec_location (Related location where comments are)
 *
 * @return string returns comment data
 */
  public static function getTotalCommentsCount($com_id = null, $com_location = null, $com_sec_id = null){
    self::$db = Database::get();
    if(isset($com_sec_id)){
      $com_count = self::$db->select("
          SELECT
            *
          FROM
            ".PREFIX."helper_comments
          WHERE
            (com_id = :com_id)
          AND
            (com_location = :com_location)
          AND
            (com_sec_id = :com_sec_id)
          ",
        array(':com_id' => $com_id,
              ':com_location' => $com_location,
              ':com_sec_id' => $com_sec_id
            ));
      $com_total = count($com_count);
    }else{
      $com_count = self::$db->select("
          SELECT
            *
          FROM
            ".PREFIX."helper_comments
          WHERE
            (com_id = :com_id OR com_sec_id = :com_id)
          AND
            (com_location = :com_location OR com_location = :com_sec_location)
          ",
        array(':com_id' => $com_id,
              ':com_location' => $com_location,
              ':com_sec_location' => "Sec".$com_location
            ));
      $com_total = count($com_count);
    }
    return $com_total;
  }

  /**
 * getInCommentsUsers
 *
 * gets comment count for all comments releated to com_id
 *
 * @param int $com_id (ID of post where comments are)
 * @param string $com_location (Section of site where comments are)
 * @param string $com_owner_userid (User ID of poster to exclude)
 *
 * @return string returns comment data
 */
  public static function getInCommentsUsers($com_id = null, $com_location = null, $com_owner_userid = null){
    $com_location = str_replace("Sec", "", $com_location);
    self::$db = Database::get();
    $com_user_ids = self::$db->select("
        SELECT
          com_owner_userid
        FROM
          ".PREFIX."helper_comments
        WHERE
          (com_id = :com_id)
        AND
          (com_location = :com_location OR com_location = :com_location2)
        AND NOT
          (com_owner_userid = :com_owner_userid)
        GROUP BY
          com_owner_userid
        ",
      array(':com_id' => $com_id,
            ':com_location' => $com_location,
            ':com_location2' => "Sec".$com_location,
            ':com_owner_userid' => $com_owner_userid
          ));
    /** Get all user emails **/
    $com_user_emails = [];
    foreach ($com_user_ids as $row) {
      $com_user_emails[] = CurrentUserData::getUserEmail($row->com_owner_userid);
    }
    return $com_user_emails;
  }


}

?>
