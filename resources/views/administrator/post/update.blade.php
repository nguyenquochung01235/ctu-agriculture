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
                <form method="post" action="/administrator/post/active/{{$post->id_post}}">
                    <div class="card-body">
                        
                    <div class="info-box" style="max-width: 300px;">
                        <span class="info-box-icon"><img style="border-radius: 100%;" src="{{$post->avatar}}" alt=""></span>

                        <div class="info-box-content">
                            <span class="info-box-number">{{$post->fullname}}</span>
                            <span style="font-style: italic;">{{$post->updated_at}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                        
                        <div class="form-group">
                            <label for="exampleInputEmail1">Tựa đề</label>
                            <input value="{{$post->title_post}}" type="text" class="form-control" id="newTitle" name="name_post" disabled>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Lượt xem</label>
                            <input value="{{$post->view}}" type="text" class="form-control" id="newTitle" name="name_post" disabled>
                        </div>

                        <div class="form-group">
                            
                            <img id="img_news" style="max-width: 350px;" src="{{$post->image}}" alt="">
                          
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Mô tả ngắn</label>
                            <input value="{{$post->short_description}}" type="text" class="form-control" id="newTitle" name="name_post" disabled>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Mô tả</label>
                            <input value="{{$post->description}}" type="text" class="form-control" id="newTitle" name="name_post" disabled>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Nội dung</label>
                            </br>
                            {{$post->content}}
                        </div>

                        
                        <div class="form-group">
                            <label for="exampleInputEmail1">Trạng thái</label>
                            <input value="{{ $post->status == 1 ? 'Đang hoạt động' : 'Chờ xét duyệt'}}" type="text" class="form-control" id="newTitle" name="active" disabled>
                        </div>
                        
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        @if($post->status == 1)
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