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
                <th style="max-width: 200px;">Tên bài viết</th>
                <th>Người viết</th>
                <th>Hình ảnh</th>
                <th>Mô tả</th>
                <th>Được tạo ngày</th>
                <th >Status</th>
                <th style="width: 50px; text-align: center;">Edit</th>

              </tr>
            </thead>
            <tbody>
              @foreach($post as $key => $data)
              <tr>
                <td>{{$data->id_post}}</td>
                <td >{{$data->title_post}}</td>
                <td >{{$data->fullname}}</td>
                <td><img style="max-width: 150px" src="{{$data->image}}" alt=""></td>
                <td style="max-width: 280px;">{{$data->description}}</td>
                <td>{{$data->updated_at}}</td>

                <td>
                  @if($data->status)
                  <button type="button" class="btn btn-sm btn-success" disabled>Đang hoạt động</button>
                  @else
                  <button type="button" class="btn btn-sm btn-danger" disabled>Chờ xét duyệt</button>
                  @endif
                </td>
                <td tyle="text-align: center;">
                  <button type="button" class="btn btn-sm btn-primary"><a style="color: #fff;" href="/administrator/post/view/{{$data->id_post}}"><i class="fas fa-eye"></i></a></button>

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