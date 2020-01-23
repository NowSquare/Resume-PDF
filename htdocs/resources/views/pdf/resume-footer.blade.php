<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ url('css/app.css') }}">
    <script>
        function subst() {
            var vars={};
            var x=document.location.search.substring(1).split('&');
            for (var i in x) {var z=x[i].split('=',2);vars[z[0]] = unescape(z[1]);}
            var x=['frompage','topage','page','webpage','section','subsection','subsubsection'];
            for (var i in x) {
                var y = document.getElementsByClassName(x[i]);
                for (var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]];
            }
        }
    </script>
    <style type="text/css">
    table {
      font-size: 14px;
    }
    </style>
  </head>
  <body onload="subst()">
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td>
          {{ \Carbon\Carbon::now()->settings(['locale' => str_replace('-', '_', $user->locale)])->isoFormat('LL') }}
<?php if ($user->premium != 1) { ?>
          - <a href="{{ config('default.app_url') }}" class="link">{{ config('default.app_url') }}</a>
<?php } ?>
        </td>
        <td align="right">
          {!! trans('app.page_of_', ['page' => '<span class="page"></span>', 'topage' => '<span class="topage"></span>']) !!}
        </td>
      </tr>
    </table>
  </body>
</html>