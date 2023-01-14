@extends('layouts.dashboard')

@section('pagetitle')
	Khoa
@endsection

@section('content')
	<div class="card mb-3">
		<div class="bg-holder d-none d-lg-block bg-card" style="background-image:url('{{ asset('public/assets/img/illustrations/corner-4.png') }}');"></div>
		<div class="card-body position-relative">
			<div class="row">
				<div class="col-lg-8">
					<nav style="--falcon-breadcrumb-divider:'»';" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a ><i class="fad fa-home-alt"></i></a></li>
							{{-- href="{{ route('dashboard.danhmuc.home') }}" --}}
							<li class="breadcrumb-item"><a href="{{ route('supmanager.home') }}">Phòng đào tạo</a></li>
							<li class="breadcrumb-item active" aria-current="page">Khoa</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	
	<div class="card mb-0">
		<div class="card-header bg-light">
			<h5 class="mb-0">Khoa</h5>
		</div>
		<div class="card-body pb-0">
			<p>
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalThem"><i class="fal fa-plus"></i> Thêm</button>
				<a href="#nhap" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importModal"><i class="fal fa-upload"></i> Nhập từ Excel</a>
				<a href="{{route('supmanager.khoa.xuat')}}" class="btn btn-success"><i class="fal fa-download"></i> Xuất ra Excel</a>
			</p>
			<div class="table-responsive scrollbar">
				<table id="DataList" class="table table-bordered table-hover table-sm overflow-hidden">
					<thead>
						<tr>
							<th class="text-nowrap" width="5%">#</th>
							<th class="text-nowrap" width="30%">Mã khoa</th>
							<th class="text-nowrap" width="30%">Tên khoa</th>
							<th class="text-nowrap" width="5%">Sửa</th>
							<th class="text-nowrap" width="5%">Xóa</th>
						</tr>
					</thead>
					<tbody>
						@foreach($khoa as $value)
							<tr>
								<td class="align-middle">{{ $loop->iteration }}</td>
								<td class="align-middle">{{ $value->MaKhoa }}</td>
								<td class="align-middle">{{ $value->TenKhoa }}</td>
								<td class="align-middle text-center"><a href="#sua" data-bs-toggle="modal" data-bs-target="#myModalEdit" onclick="getCapNhat('{{ $value->MaKhoa }}', '{{ $value->TenKhoa }}'); return false;"><i class="fal fa-edit"></i></a></td>
								<td class="align-middle text-center pe-1"><a href="#xoa" data-bs-toggle="modal" data-bs-target="#myModalDelete" onclick="getXoa('{{ $value->MaKhoa }}'); return false;"><i class="fal fa-trash-alt text-danger"></i></a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<form action="{{ route('supmanager.khoa.them') }}" method="post" class="needs-validation" novalidate>
		@csrf
		<div class="modal fade" id="myModalThem" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="myModalLabel">Thêm khoa</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label" for="MaKhoa"><span class="badge bg-info">1</span> Mã khoa <span class="text-danger fw-bold">*</span></label>
							<input type="text" class="form-control @error('MaKhoa') is-invalid @enderror" id="MaKhoa" name="MaKhoa" value="{{ old('MaKhoa') }}" required />
							@error('MaKhoa')
								<div class="invalid-feedback"><strong>{{ $message }}</strong></div>
							@enderror
						</div>
						<div class="mb-3">
							<label class="form-label" for="TenKhoa"><span class="badge bg-info">2</span> Tên khoa <span class="text-danger fw-bold">*</span></label>
							<input type="text" class="form-control @error('TenKhoa') is-invalid @enderror" id="TenKhoa" name="TenKhoa" value="{{ old('TenKhoa') }}" required />
							@error('TenKhoa')
								<div class="invalid-feedback"><strong>{{ $message }}</strong></div>
							@enderror
						</div>			
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fal fa-save"></i> Thực hiện</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<form action="{{ route('supmanager.khoa.sua') }}" method="post" class="needs-validation" novalidate>
		@csrf
		<input type="hidden" id="id_edit" name="id_edit" value="" />
		<div class="modal fade" id="myModalEdit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="myModalLabelEdit">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="myModalLabelEdit">Cập nhật thông tin khoa</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label" for="MaKhoa_edit"><span class="badge bg-info">1</span> Mã khoa <span class="text-danger fw-bold">*</span></label>
							<input type="text" class="form-control @error('MaKhoa_edit') is-invalid @enderror" id="MaKhoa_edit" name="MaKhoa_edit" value="{{ old('MaKhoa_edit') }}" required />
							@error('MaKhoa_edit')
								<div class="invalid-feedback"><strong>{{ $message }}</strong></div>
							@enderror
						</div>
						<div class="mb-3">
							<label class="form-label" for="TenKhoa_edit"><span class="badge bg-info">2</span> Tên khoa <span class="text-danger fw-bold">*</span></label>
							<input type="text" class="form-control @error('TenKhoa_edit') is-invalid @enderror" id="TenKhoa_edit" name="TenKhoa_edit" value="{{ old('TenKhoa_edit') }}" required />
							@error('TenKhoa_edit')
								<div class="invalid-feedback"><strong>{{ $message }}</strong></div>
							@enderror
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fal fa-save"></i> Thực hiện</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<form action="{{ route('supmanager.khoa.xoa') }}" method="post" class="needs-validation" novalidate>
		@csrf
		<input type="hidden" id="MaKhoa_delete" name="MaKhoa_delete"/>
		<div class="modal fade" id="myModalDelete" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="myModalLabelDelete">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="myModalLabelDelete">Xóa khoa</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body fw-bold text-danger text-center">
						<p class="mt-0 mb-1"><i class="fad fa-question-circle fa-3x"></i></p>
						<p class="mt-1 mb-0">Xác nhận xóa? Hành động này không thể phục hồi.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fal fa-times"></i> Hủy bỏ</button>
						<button type="submit" class="btn btn-danger"><i class="fal fa-trash-alt"></i> Thực hiện</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<form action="{{ route('supmanager.khoa.nhap') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
		@csrf
		<div class="modal fade" id="importModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="importModalLabel">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="importModalLabel">Nhập từ Excel</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="mb-0">
							<label class="form-label" for="file_excel"><span class="badge bg-info">1</span> Chọn tập tin Excel <span class="text-danger fw-bold">*</span></label>
							<input type="file" class="form-control" id="file_excel" name="file_excel" required />
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fal fa-times"></i> Hủy bỏ</button>
						<button type="submit" class="btn btn-danger"><i class="fal fa-upload"></i> Nhập dữ liệu</button>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection
	
@section('javascript')
	<script>
		function getCapNhat(MaKhoa, TenKhoa) {
			$('#id_edit').val(MaKhoa);
			$('#MaKhoa_edit').val(MaKhoa);
			$('#TenKhoa_edit').val(TenKhoa);
		}
		
		function getXoa(id) {
			$('#MaKhoa_delete').val(id);
		}
		
		@if($errors->has('MaKhoa') || $errors->has('TenKhoa'))
			var myModalThem = new bootstrap.Modal(document.getElementById('myModalThem'));
			myModalThem.show();
		@endif
		
		@if($errors->has('MaKhoa_edit') || $errors->has('TenKhoa_edit'))
			var myModalEdit = new bootstrap.Modal(document.getElementById('myModalEdit'));
			myModalEdit.show();
		@endif
	</script>
@endsection