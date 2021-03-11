<a href="/" class="navbar-brand d-flex">
    <img style="border-radius: 50%;" src="{{\Illuminate\Support\Facades\Auth::user()->photo ? \Illuminate\Support\Facades\Auth::user()->photo->file : asset('img/avatar.png')}}"
         width="100" height="100" alt="logo" class="d-none d-md-block">
    <h3 style="font-family: Tahoma; font-size: 30px;" class="d-sm-inline align-middle text-white mt-4 ml-2">
        {{\Illuminate\Support\Facades\Auth::user() ? ucfirst(\Illuminate\Support\Facades\Auth::user()->username) : "No Name"}}
    </h3>
</a>
