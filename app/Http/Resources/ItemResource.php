<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $size="";
        // if(str_contains($this->package_status,"no")){
            
            $size = $this->countable_unit. $this->countable_size.' '.$this->unit_from;
            if($this->item_package_qty != 1){
                $size=$this->item_package_qty. ' x '.$this->countable_unit. $this->countable_size.' '.$this->unit_from . ' / ' .$this->unit_to;
            }

            return [
                'id' => $this->id,
                'product_name' => $this->name,
                'size' => $size ,
                'item_size_id' => $this->item_size_id,
                'barcode' => $this->package_barcode ,
                'class' => $this->class_name ,
                'category' => $this->category_name,
                'packaging_id' =>  $this->item_package_id               
            ];
        // }
        // else{
        //     if(str_contains($this->sizeoption,"no") && ($this->unit_from==$this->unit_to)){
        //         $size=$this->countable_unit. ' ' . $this->unit_from;
        //     }
        //     else{
        //         $size=$this->item_package_qty. ' x '.$this->countable_unit. $this->countable_size.' '.$this->unit_from . ' / ' .$this->unit_to;
        //     }
        // }
        
        return [
            'id' => $this->id,
            'product_name' => $this->name,
            'size' => $size,
            'item_size_id' => $this->item_size_id,
            'barcode' => $this->package_barcode ,
            'class' => $this->class_name ,
            'category' => $this->category_name,
            
            'packaging_id' => $this->item_package_id
        ];
    }
}
