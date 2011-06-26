<?php

//Create, Read, Update, Delete
abstract class Crud {
    protected $name;
    protected $redirectTo;

    protected $model;

    public function __construct($options)
    {
        $this->redirectTo = "index.php?action=manageNotes&amp;id=".$_POST['goat_id'];
    }

    public function add()
    {
        $action = "Add";

        $content = array('
            <h3>'.ucfirst(strtolower($this->name)).'s Administration</h3>
            <div class="section">');

        array_push($content, new Form($this->model->getFormDetails($id, $goat_id, $note, $action)));

        array_push($content,'
            </div>');
    }

    public function update()
    {
        $action = "Edit";

        $content = array('
            <h3>'.ucfirst(strtolower($this->name)).'s Administration</h3>
            <div class="section">');

        array_push($content,'
            </div>');
    }

    public function process($action)
    {
        $success = $this->model->update();

        if ($success) {
            $msg = ucfirst(strtolower($this->name))." ".strtolower($action)."ed successfully";
            $status = "good";
        } else {
            $msg = "Error ".strtolower($action)."ing ".strtolower($this->name);
            $status = "bad";
        }

        return array($status, $msg, $this->redirectTo);
    }

    public abstract function delete()
    {

    }

    public abstract function adminList()
    {

    }
}
?>