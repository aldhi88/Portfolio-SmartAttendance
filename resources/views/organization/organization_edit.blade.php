<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <button class="btn btn-primary">Add New Data</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col col-md-4">
            <form wire:submit="submit">

                <div class="form-group">
                    <label>ID Pengguna</label>
                    <input autofocus="" type="text" readonly="" disabled="" wire:model="username" class="bg-light form-control ">
                    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="form-group">
                    <label>Ubah Sandi Login</label>
                    <input type="text" wire:model.lazy="password" placeholder="Biarkan kosong jika tidak ingin merubah" class="form-control ">
                    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
                </div>

                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>

        </div>
    </div>
    {{-- @include('organization.atc.organization_data_atc') --}}

</div>
