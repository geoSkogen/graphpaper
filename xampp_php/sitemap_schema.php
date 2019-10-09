<?

class Schema {

  public $data_index = array();
  public $data_assoc = array();
  public $labeled_columns = array();
  public $labeled_rows = array();

  function __construct($filename, $path) {
    $this->data_index = $this->import_csv_index($filename, $path);
    $this->data_assoc = $this->make_assoc($filename, $path);
  }

?>  
