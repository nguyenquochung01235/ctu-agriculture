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
        <div class="card-body">

         

          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th style="width: 10px;">ID</th>
                <th style="max-width: 200px;">Tên Hợp Tác Xã</th>
                <th>Số điện thoại</th>
                <th>Hình ảnh</th>
                <th>Được tạo ngày</th>
                <th >Status</th>
                <th style="width: 50px; text-align: center;">Edit</th>

              </tr>
            </thead>
            <tbody>
              @foreach($hoptacxa as $key => $data)
              <tr>
                <td>{{$data->id_hoptacxa}}</td>
                <td style="max-width: 280px;">{{$data->name_hoptacxa}}</td>
                <td style="max-width: 280px;">{{$data->phone_number}}</td>
                <td><img style="max-width: 150px" src="{{$data->thumbnail}}" alt=""></td>
                <td>{{$data->created_at}}</td>

                <td>
                  @if($data->active)
                  <button type="button" class="btn btn-sm btn-success" disabled>Đang hoạt động</button>
                  @else
                  <button type="button" class="btn btn-sm btn-danger" disabled>Chờ xét duyệt</button>
                  @endif
                </td>
                <td tyle="text-align: center;">
                  <button type="button" class="btn btn-sm btn-primary"><a style="color: #fff;" href="/administrator/hoptacxa/view/{{$data->id_hoptacxa}}"><i class="fas fa-eye"></i></a></button>

                </td>

              </tr>
              @endforeach


            </tbody>

          </table>
         
        <!-- /.card-body -->
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
@endsection