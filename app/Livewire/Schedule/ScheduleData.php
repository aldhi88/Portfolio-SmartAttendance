<?php

namespace App\Livewire\Schedule;

use App\Repositories\Interfaces\MasterScheduleFace;
use Livewire\Attributes\On;
use Livewire\Component;

class ScheduleData extends Component
{
    protected $masterScheduleRepo;
    public function boot(MasterScheduleFace $masterScheduleRepo)
    {
        $this->masterScheduleRepo = $masterScheduleRepo;
    }

    // delete section
    public $deleteId;
    #[On('setDeleteId')]
    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }
    public function wireDelete()
    {
        if($this->masterScheduleRepo->delete($this->deleteId)){
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirmDelete');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data berhasil dihapus.']);
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $deleteMultipleId;
    #[On('setDeleteMultipleId')]
    public function setDeleteMultipleId($ids)
    {
        $this->deleteMultipleId = $ids;
    }
    public function deleteMultiple()
    {
        if($this->masterScheduleRepo->deleteMultiple($this->deleteMultipleId)){
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
            return;
        }
        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end delete section

    public $pass;
    public function render()
    {
        return view('schedule.schedule_data');
    }
}
