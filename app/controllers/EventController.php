<?php
use Phalcon\Mvc\View;
class Eventcontroller extends ControllerBase{

  public function beforeExecuteRoute(){ // function ที่ทำงานก่อนเริ่มการทำงานของระบบทั้งระบบ
	  if(!$this->session->has('memberAuthen')) // ตรวจสอบว่ามี session การเข้าระบบ หรือไม่
    		 $this->response->redirect('authen');   
   } 

  public function indexAction(){
    $events = Event::find();
        $this->view->events = $events;
  }

  public function signUpAction(){
    if($this->request->isPost()){
        $photoUpdate = '';
        $photoUpdate = '';
            if ($this->request->hasFiles() == true) {
                $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
                $uploads = $this->request->getUploadedFiles();

                $isUploaded = false;
                foreach ($uploads as $upload) {
                    if (in_array($upload->gettype(), $allowed)) {
                        $photoName = md5(uniqid(rand(), true)) . strtolower($upload->getname());
                        $path = '../public/img/' . $photoName;
                        ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
                    }
                }

                if ($isUploaded) {
                    $photoUpdate = $photoName;
                }

            } else {
                die('You must choose at least one file to send. Please try again.');
            }
      $name = trim($this->request->getPost('nickname')); // รับค่าจาก form
      $datee = trim($this->request->getPost('date')); // รับค่าจาก form
      $firstname = trim($this->request->getPost('data')); // รับค่าจาก form
      
      $event = new Event();
      $event->name = $name;
      $event->date = $datee;
      $event->detail = $firstname;
      $event->picture = $photoUpdate;
      $event->save();
      $this->response->redirect('event');
      }
  }
  public function editAction($id)
  {
      if ($this->request->isPost()) {

          $photoUpdate = '';
          if ($this->request->hasFiles(true) == true) {
              $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
              $uploads = $this->request->getUploadedFiles();

              $isUploaded = false;
              foreach ($uploads as $upload) {
                  if (in_array($upload->gettype(), $allowed)) {
                      $photoName = md5(uniqid(rand(), true)) . strtolower($upload->getname());
                      $path = '../public/img/' . $photoName;
                      ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
                  }
              }
              if ($isUploaded) {
                  $photoUpdate = $photoName;
              }   
          }

          $name = trim($this->request->getPost('nickname')); // รับค่าจาก form
          $datee = trim($this->request->getPost('date')); // รับค่าจาก form
          $firstname = trim($this->request->getPost('data')); // รับค่าจาก form
          
          $event = Event::findFirst($id);
          $event->name = $name;
          $event->date = $datee;
          $event->detail = $firstname;
          $event->picture = $photoUpdate;
          $event->save();
          $this->response->redirect('event');
  }
      $event = Event::findFirst($id);
      $this->view->event = $event;
  }

  public function deleteAction($id)
  {
      $toDeleteEvent = Event::findFirst($this->request->getPost('id'));
      $toDeleteEvent->delete();
      $this->response->redirect('event');
  }
}
