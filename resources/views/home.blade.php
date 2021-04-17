@extends('layouts.master')

@section("head")
    <title>Ana Sayfa</title>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
@endsection
@section('content')
    <div class="row" ><!-- style="background-image: url('https://nayn.co/wp-content/uploads/2016/07/uzay-2.jpg')"-->
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div id="sure" class="bg-success " style="border-radius:50px;height: 50px;width: 100%;display: block">

                <h2 align="center"  ><span style="color: #3D5170">Yeni Hatırlatıcı Hakkına :</span>
                    <?php $mytime = Carbon\Carbon::now();
                    $son_paylasim = \App\Paylasimlar::where('user_id',\Auth::user()->id)->orderBy('created_at','desc')->first();
                    ?>
                    @if(isset($son_paylasim))
                        <?php  $dif=$mytime->diff($son_paylasim->created_at); ?>
                        <span>{{--<span class="count">{{0-$dif->days }}</span> Gün--}} <span class="count">{{$dif->h }} </span> Saat <span class="count">{{59-$dif->i}}</span> Dakika </span>
                    @else
                        <span>Paylaşımı Bulunmuyor !</span>
                    @endif
                </h2>
            </div><br>
            <script>
                $('.count').each(function () {
                    $(this).prop('Counter',0).animate({
                        Counter: $(this).text()
                    }, {
                        duration: 2500,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                });
            </script>
            <div id="yeni_paylasim" >
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <form action="{{route('yeni_post_ekle')}}" method="POST" >
                            @csrf
                            <div>
                                <div >
                                    <input type="text" name="etiket" align="right" class="form-control is-valid" autocomplete="off" placeholder="  Başlık giriniz... ">
                                </div>
                                <br>
                                <div>
                                    <input type="text" name="metin" class="form-control is-valid" style="height: 80px;"  autocomplete="off" placeholder="   Mesajınızı yazın... ">
                                </div>
                                <br>
                                <div style="position:absolute; right:20px;">
                                    <button class="bg-success   text-white text-center p-3" style="width: 200px;border-radius:20px;">Paylaş</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                if(isset($dif)){
                    if (30-$dif->days<=0 and  24-$dif->h<=0  and 59-$dif->i<=0 )//Kalan hakkı buradan ayarlıyoruz
                    {
                        echo "
                                     <script>
                                          document.getElementById('yeni_paylasim').style.display='block';
                                          document.getElementById('sure').style.display='none';
                                     </script>

                                 ";
                    }
                    else{
                        echo "
                                <script>
                                    document.getElementById('sure').style.display='block';
                                    document.getElementById('yeni_paylasim').style.display='none';
                                </script>

                            ";
                    }
                }
                ?>
            </div>
            <br><br><br><br>
            @if(count($errors))
                <div class="form-group">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif<hr>
            <div align="center">
                <a    href="{{route('home')}}" style="width: 40%;" class="col-md-6 btn btn-secondary">En Yeniler</a>
                <a href="{{route('homed')}}" class="btn col-md-6" style="width: 40%; background-color: #28AF4F;color:white">En İyiler</a>
            </div>
            <hr>
            <div class="row" >

                @foreach($butun_paylasimlar as $key_paylasim => $paylasim_satir)
                    <?php $paylasan_kisi = \App\User::where('id',$paylasim_satir->user_id)->first();?>

                    <div class="col-lg-4" >
                        <div class="card card-small card-post mb-4" name="kart">
                            <div class="card-body row ">
                                <h5 class="card-title col-md-10 ">{{$paylasim_satir->baslik}} </h5>
                                <div class="card-title col-md-2 ">
                                    <span style="font-size:20px; cursor:pointer;" onclick="puan_guncelle(this,1,{{$paylasim_satir->id}})" >&uarr;</span>
                                    <br>
                                    <?php $puanlar = \App\puanlar::where('paylasimlars_id',$paylasim_satir->id)->get();
                                    $toplam_puan =0 ;
                                    foreach ($puanlar as $puan){
                                        $toplam_puan+=$puan->verilen_oy;
                                    }
                                    if($toplam_puan<0)$toplam_puan=0;
                                    ?>
                                    <span class="paylasilanin_puani">{{$toplam_puan}}</span>
                                    <br>
                                    <span style="font-size:20px; cursor:pointer;" onclick="puan_guncelle(this,0,{{$paylasim_satir->id}})" >&darr;</span>
                                </div>
                                <p class="card-text text-muted ">{{$paylasim_satir->paylasim}}</p>
                            </div>

                            <div class="card-footer border-top d-flex" style="background-color: #28A745">
                                <div class="card-post__author d-flex">
                                    <div class="col-md-7 d-flex flex-column justify-content-center ml-3" >
                                        @if(isset($paylasan_kisi))
                                            <span class="card-post__author-name" style="color: white">{{$paylasan_kisi->name}}</span>
                                        @else
                                            <span class="card-post__author-name">paylasan kisi bulunamadi</span>
                                        @endif
                                    </div>
                                    <div class="col-md-5 d-flex flex-column justify-content-center ml-3">
                                        <small class="text-white">{{$paylasim_satir->created_at}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>


    </div>
    <br>


    <script>
        function puan_guncelle(event,istenilen,id) {
            var puan = $(event).parent().find('.paylasilanin_puani');
            $.ajax({
                type:"post",
                url:"/panel/ajax_puan_guncelle",
                data:{
                    _token:"{{csrf_token()}}",
                    islem:istenilen,
                    id:id
                },
                success:function(result){
                    $(puan).html(result);
                }
            });
        }
    </script>
@endsection
