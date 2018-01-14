<?php


class Qcli
{
    private $error = 0;
    private $action;
    private static $actions;
    private $name;
    private static $Cfile;
    private static $Mfile;
    private static $Vfile;
    private $files;

    public function __construct($data = array())
    {
        extract($data);
        $this->action = $action;
        $this->name = $name;
        $this->valid();
    }
    public function valid(){

        if(empty($this->action)) $this->error = 1;

        self::$actions = ['--help','create','ls'];
        if($this->error == 0){
            self::$Cfile = CONTROLLERS.$this->name . ".php";
            self::$Vfile = VIEW ."pages".SP.$this->name .".html";
            self::$Mfile = MODEL.$this->name . ".php";
        }
        if($this->action == '--help') $this->help();
        if($this->action == 'create') $this->create();
        if($this->action == 'ls') $this->ls();
        if($this->action == 'remove') $this->remove();

        if($this->error == 1) print("use qourota --help for more information \n"); exit();


    }
    public function create(){
        if(empty($this->name)) exit("Pleas set page name\nfor more information use : qourota --help\n");
        $this->files = [self::$Cfile,self::$Vfile,self::$Mfile];
        if($this->error === 0 and $this->action == "create"){
            if(file_exists(self::$Cfile)){
                echo "this page already exist pleas use another name\n";
            }else{
            $newCntrl = fopen(self::$Cfile,"w");
            fwrite($newCntrl, '
<?php


class '.$this->name.' extends controller{

    public function index()
    {
        parent::view();
        parent::model();
        $this->view->render("pages/'.$this->name.'.html");
        $this->model->render("'.$this->name.'",0);
    }
}
');
            fclose($newCntrl);
            $newView = fopen(self::$Vfile,"w");
            fwrite($newView,"<h1>This is $this->name</h1> ");
            fclose($newView);
            $newModel = fopen(self::$Mfile,"w");
            fwrite($newModel,"<?php echo'".$this->name."';");
            fclose($newModel);
            echo "the page ".$this->name." was created with success\n";
          }
        }

    }
    public function help(){
        print("to create new page use : qourota create page_name\n");
        print("to remove page use : qourota remove page_name\n");
        print("to show pages list use : qourota ls\n");
    }
    public function ls(){
        $ls = scandir(CONTROLLERS);
        echo "this is all pages you have\n";
        foreach ($ls as $el){
            $el = rtrim($el,".php");
            echo "  ".$el ."\n";
        }
        echo "\n";
    }
    public function remove(){
        if(empty($this->name)) exit("Pleas set page name\nfor more information use : qourota --help\n");
        $this->files = [self::$Cfile,self::$Vfile,self::$Mfile];
        foreach ($this->files as $fl):
            if(!file_exists($fl)) $this->error = 1;
        endforeach;
        if($this->error == 0):
            foreach ($this->files as $fl):
                unlink($fl);
            endforeach;
            echo "the page ".$this->name." removed with success\n";
        else :
            echo "File not exist try with qourota ls to show files list\n";
        endif;
    }

}
