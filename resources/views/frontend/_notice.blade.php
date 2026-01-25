<section class="notice-section">
    <div class="container"
         style="background:black; height:40px; display:flex; align-items:center; justify-content:center;">

        <marquee
            behavior="scroll"
            direction="left"
            onmouseover="this.stop()"
            onmouseout="this.start()"
            style="color:#ffffff; font-size:18px; width:100%; font-weight: 700">

        <span style="padding:0 16px;">
            <b>{{ $notice->headline ?? '' }}:</b>
            {{ $notice->description ?? '' }}
        </span>

        </marquee>

    </div>
</section>
