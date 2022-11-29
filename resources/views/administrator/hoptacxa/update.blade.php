@extends('administrator.main')

@section('content')



<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$title}}</h3>

                </div>
                @include('administrator.alert')
                <!-- /.card-header -->
                <!-- Form Add Department -->
                <form method="post" action="/administrator/hoptacxa/active/{{$hoptacxa->id_hoptacxa}}">
                    <div class="card-body">
                        
                        
                        <div class="form-group">
                            <label for="exampleInputEmail1">Tên hợp tác xã</label>
                            <input value="{{$hoptacxa->name_hoptacxa}}" type="text" class="form-control" id="newTitle" name="name_hoptacxa" disabled>
                        </div>

                        <div class="form-group">
                            
                            <img id="img_news" style="max-width: 350px;" src="{{$hoptacxa->thumbnail}}" alt="">
                          
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Số điện thoại</label>
                            <input value="{{$hoptacxa->phone_number}}" type="text" class="form-control" id="newTitle" name="name_hoptacxa" disabled>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input value="{{$hoptacxa->email}}" type="text" class="form-control" id="newTitle" name="name_hoptacxa" disabled>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Địa chỉ</label>
                            <input value="{{$hoptacxa->address}}" type="text" class="form-control" id="newTitle" name="name_hoptacxa" disabled>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Mô tả</label>
                            <textarea disabled class="form-control" cols="30" rows="10">{{$hoptacxa->description}}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Chủ sở hữu</label>
                            <input value="{{$hoptacxa->fullname}}" type="text" class="form-control" id="newTitle" name="name_hoptacxa" disabled>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Số điện thoại chủ sở hữu</label>
                            <input value="{{$hoptacxa->user_phone_number}}" type="text" class="form-control" id="newTitle" name="name_hoptacxa" disabled>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Ngày tạo</label>
                            <input value="{{$hoptacxa->created_at}}" type="text" class="form-control" id="newTitle" name="name_hoptacxa" disabled>
                        </div>


                        
                        <div class="form-group">
                            <label for="exampleInputEmail1">Trạng thái</label>
                            <input value="{{ $hoptacxa->active == 1 ? 'Đang hoạt động' : 'Chờ xét duyệt'}}" type="text" class="form-control" id="newTitle" name="active" disabled>
                        </div>
                        
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        @if($hoptacxa->active == 1)
                        <button type="submit" class="btn btn-sm btn-danger">Hủy kích hoạt</button>
                        @else
                        <button type="submit" class="btn btn-sm btn-success">Kích hoạt</button>
                        @endif
                    </div>
                    @csrf
                </form>

                <!-- /.card-body -->
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>


<!-- ./wrapper -->
@endsection