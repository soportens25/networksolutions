<div>

    @if(auth()->check() && auth()->user()->empresas->contains('1'))

    <iframe src="https://calendar.google.com/calendar/embed?height=500&wkst=2&ctz=America%2FBogota&showPrint=0&showNav=0&showDate=0&showCalendars=0&showTabs=0&showTz=0&src=ZmRhNTM4YjUxYjc4Yjg2ODM4ODFlODE3NTQ5YTkyODRlNzlkNjUyYzM2MDcwYTcyZTU0OTJmZDExNWZkMWJkY0Bncm91cC5jYWxlbmRhci5nb29nbGUuY29t&color=%23AD1457" style="border-width:0" width="1000" height="500" frameborder="0" scrolling="no"></iframe>    @endif
</div>