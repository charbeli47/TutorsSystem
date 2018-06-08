<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class HomeSlider_Model extends Base_Model  
{	
	function __construct()
	{
		parent::__construct();
	}
		
	function getHomeSliderStatistics()
	{
		$query = 'SELECT (SELECT COUNT(*) FROM '.$this->db->dbprefix(TBL_HOME_SLIDER).') AS slidercount FROM '.$this->db->dbprefix(TBL_HOME_SLIDER);		
		$resultsetlimit = $this->db->query( $query );
		return $resultsetlimit->result();
	}
	
	function getData()
	{
		$query = "SELECT * FROM ".$this->db->dbprefix(TBL_HOME_SLIDER)." WHERE status='Active' ";
		return $this->db->query( $query )->result();
	}
}
?>