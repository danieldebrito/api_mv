<?php 

interface IApiCRUD{ 
	public function readAllApi($request, $response, $args);
	public function readApi($request, $response, $args);  
   	public function CreateApi($request, $response, $args);
   	public function deleteApi($request, $response, $args);
   	public function updateApi($request, $response, $args);
}