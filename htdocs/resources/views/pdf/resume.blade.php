<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ ($user->job_title != '') ? $user->name . ' - ' . $user->job_title : $user->name }}</title>
    <link rel="stylesheet" href="{{ url('css/app.css') }}">
  </head>
  <body>
    <div class="page">
      <div class="inner-page">
        <div class="container-fluid">
          <div class="row">
            <div class="col-8">
              <div style="font-family: RobTh; font-size: 3.5rem;">{{ $user->name }}</div>
              <div class="mt-1" style="font-size: 1.6rem;">{{ $user->job_title }}</div>
<?php
if (isset($resume['experience']) && count($resume['experience']) > 0) {
?>
              <div class="row mt-4">
                <div class="col">
                  <h1 class="display-4">{{ trans('app.experience') }}</h1>
                </div>
              </div>
              <div class="row my-4">
                <div class="col">
                  <table class="table table-striped mt-4 mb-5 table-bordered">
<?php
  foreach ($resume['experience'] as $item) {
?>
                    <tr>
                      <td>
                      <div class="container-fluid">
                        <div class="row mb-2">
                          <div class="col-1">
                            <svg class="icon icon-s" viewBox="0 0 24 24">
                              <path d="M9,10H7V12H9V10M13,10H11V12H13V10M17,10H15V12H17V10M19,3H18V1H16V3H8V1H6V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V8H19V19Z" />
                            </svg>
                          </div>
                          <div class="col-11">
                            {{ $item['date'] }}
                          </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-1">
                            <svg class="icon icon-s" viewBox="0 0 24 24">
<?php 
    if ($item['type'] == 'education') { 
?>
                              <path d="M12,3L1,9L12,15L21,10.09V17H23V9M5,13.18V17.18L12,21L19,17.18V13.18L12,17L5,13.18Z" />
<?php
    } elseif ($item['type'] == 'work') { 
?>
                              <path d="M18,15H16V17H18M18,11H16V13H18M20,19H12V17H14V15H12V13H14V11H12V9H20M10,7H8V5H10M10,11H8V9H10M10,15H8V13H10M10,19H8V17H10M6,7H4V5H6M6,11H4V9H6M6,15H4V13H6M6,19H4V17H6M12,7V3H2V21H22V7H12Z" />
<?php
    }
?>
                            </svg>
                          </div>
                          <div class="col-11">
                          {!! $item['name'] !!}
                          </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-1">
                            <svg class="icon icon-s" viewBox="0 0 24 24">
                              <path d="M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z" />
                            </svg>
                          </div>
                          <div class="col-11">
                          {!! $item['location'] !!}
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-1">
                          </div>
                          <div class="col-11">
                            {!! $item['description'] !!}
                          </div>
                        </div>
                      </div>
                      </td>
                    </tr>
<?php
  }
?>
                  </table>
                </div>
              </div>
<?php
}
?>
            </div>
            <div class="col-4">
              <img src="{{ $user->avatar }}" class="avatar img-fluid">

              <h3 class="text-uppercase mt-5 mb-3">{{ trans('app.profile') }}</h3>

              <p class="lead">{!! $user->bio !!}</p>
<?php
if ($resume['tags'] !== null && ! empty($resume['tags'])) {
?>
              <p class="tags">
<?php
  foreach ($resume['tags'] as $tag) {
?>
                <span class="badge badge-secondary">{!! $tag !!}</span>
<?php 
  }
?>
              </p>
<?php
}
?>
              <h3 class="text-uppercase mt-5 mb-3">{{ trans('app.contact') }}</h3>

              <table class="table table-borderless break-word">
<?php
if ($user->contact_phone !== null) {
?>
                <tr>
                  <td width="45">
                    <svg class="icon" viewBox="0 0 24 24">
                      <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z" />
                    </svg>
                  </td>
                  <td>{!! $user->contact_phone !!}</td>
                </tr>
<?php
}

if ($user->contact_email !== null) {
?>
                <tr>
                  <td>
                    <svg class="icon" viewBox="0 0 24 24">
                      <path d="M22 6C22 4.9 21.1 4 20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6M20 6L12 11L4 6H20M20 18H4V8L12 13L20 8V18Z" />
                    </svg>
                  </td>
                  <td><a href="mailto:{!! $user->contact_email !!}" class="link" target="_blank">{!! $user->contact_email !!}</a></td>
                </tr>
<?php
}

if ($user->website !== null) {
?>
                <tr>
                  <td width="45">
                    <svg class="icon" viewBox="0 0 24 24">
                      <path d="M16.36,14C16.44,13.34 16.5,12.68 16.5,12C16.5,11.32 16.44,10.66 16.36,10H19.74C19.9,10.64 20,11.31 20,12C20,12.69 19.9,13.36 19.74,14M14.59,19.56C15.19,18.45 15.65,17.25 15.97,16H18.92C17.96,17.65 16.43,18.93 14.59,19.56M14.34,14H9.66C9.56,13.34 9.5,12.68 9.5,12C9.5,11.32 9.56,10.65 9.66,10H14.34C14.43,10.65 14.5,11.32 14.5,12C14.5,12.68 14.43,13.34 14.34,14M12,19.96C11.17,18.76 10.5,17.43 10.09,16H13.91C13.5,17.43 12.83,18.76 12,19.96M8,8H5.08C6.03,6.34 7.57,5.06 9.4,4.44C8.8,5.55 8.35,6.75 8,8M5.08,16H8C8.35,17.25 8.8,18.45 9.4,19.56C7.57,18.93 6.03,17.65 5.08,16M4.26,14C4.1,13.36 4,12.69 4,12C4,11.31 4.1,10.64 4.26,10H7.64C7.56,10.66 7.5,11.32 7.5,12C7.5,12.68 7.56,13.34 7.64,14M12,4.03C12.83,5.23 13.5,6.57 13.91,8H10.09C10.5,6.57 11.17,5.23 12,4.03M18.92,8H15.97C15.65,6.75 15.19,5.55 14.59,4.44C16.43,5.07 17.96,6.34 18.92,8M12,2C6.47,2 2,6.5 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                    </svg>
                  </td>
                  <td><a href="{!! $user->website !!}" class="link" target="_blank">{!! str_replace(['http://www.', 'https://www.', 'http://', 'https://'], '', $user->website) !!}</a></td>
                </tr>
<?php
}

if ($user->linkedin !== null) {
?>
                <tr>
                  <td>
                    <svg class="icon" viewBox="0 0 24 24">
                      <path d="M21,21H17V14.25C17,13.19 15.81,12.31 14.75,12.31C13.69,12.31 13,13.19 13,14.25V21H9V9H13V11C13.66,9.93 15.36,9.24 16.5,9.24C19,9.24 21,11.28 21,13.75V21M7,21H3V9H7V21M5,3A2,2 0 0,1 7,5A2,2 0 0,1 5,7A2,2 0 0,1 3,5A2,2 0 0,1 5,3Z" />
                    </svg>
                  </td>
                  <td><a href="{!! $user->linkedin !!}" class="link" target="_blank">{!! str_replace(['http://www.', 'https://www.', 'http://', 'https://'], '', $user->linkedin) !!}</a></td>
                </tr>
<?php
}

if ($user->languages !== null) {
?>
                <tr>
                  <td width="45">
                    <svg class="icon" viewBox="0 0 24 24">
                      <path d="M12.87,15.07L10.33,12.56L10.36,12.53C12.1,10.59 13.34,8.36 14.07,6H17V4H10V2H8V4H1V6H12.17C11.5,7.92 10.44,9.75 9,11.35C8.07,10.32 7.3,9.19 6.69,8H4.69C5.42,9.63 6.42,11.17 7.67,12.56L2.58,17.58L4,19L9,14L12.11,17.11L12.87,15.07M18.5,10H16.5L12,22H14L15.12,19H19.87L21,22H23L18.5,10M15.88,17L17.5,12.67L19.12,17H15.88Z" />
                    </svg>
                  </td>
                  <td>{!! $user->languages !!}</td>
                </tr>
<?php
}

if ($user->date_of_birth !== null) {
?>
                <tr>
                  <td width="45">
                    <svg class="icon" viewBox="0 0 24 24">
                      <path d="M12,6C13.11,6 14,5.1 14,4C14,3.62 13.9,3.27 13.71,2.97L12,0L10.29,2.97C10.1,3.27 10,3.62 10,4A2,2 0 0,0 12,6M16.6,16L15.53,14.92L14.45,16C13.15,17.29 10.87,17.3 9.56,16L8.5,14.92L7.4,16C6.75,16.64 5.88,17 4.96,17C4.23,17 3.56,16.77 3,16.39V21A1,1 0 0,0 4,22H20A1,1 0 0,0 21,21V16.39C20.44,16.77 19.77,17 19.04,17C18.12,17 17.25,16.64 16.6,16M18,9H13V7H11V9H6A3,3 0 0,0 3,12V13.54C3,14.62 3.88,15.5 4.96,15.5C5.5,15.5 6,15.3 6.34,14.93L8.5,12.8L10.61,14.93C11.35,15.67 12.64,15.67 13.38,14.93L15.5,12.8L17.65,14.93C18,15.3 18.5,15.5 19.03,15.5C20.11,15.5 21,14.62 21,13.54V12A3,3 0 0,0 18,9Z" />
                    </svg>
                  </td>
                  <td>{{ $user->date_of_birth->settings(['locale' => str_replace('-', '_', $user->locale)])->isoFormat('LL') }}</td>
                </tr>
<?php
}

if ($user->address1 !== null || $user->address2 !== null || $user->address3 !== null) {
?>
                <tr>
                  <td>
                    <svg class="icon" viewBox="0 0 24 24">
                      <path d="M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z" />
                    </svg>
                  </td>
                  <td>
                    {!! ($user->address1 !== null) ? $user->address1 . '<br>' : '' !!}
                    {!! ($user->address2 !== null) ? $user->address2 . '<br>' : '' !!}
                    {!! ($user->address3 !== null) ? $user->address3 : '' !!}
                  </td>
                </tr>
<?php
}
?>
              </table>
            </div>
          </div>
<?php
if (isset($resume['projects']) && count($resume['projects']) > 0) {
?>
          <div class="row mt-5" style="page-break-before: always;">
            <div class="col">
              <h1 class="display-4">{{ trans('app.projects') }}</h1>
            </div>
          </div>
<?php
  foreach ($resume['projects'] as $index => $item) {
?>

                <div class="row my-4 page-break-inside-avoid">
<?php
    if ($item['image'] !== null) {
?>
                  <div class="col-6">
                    <div class="card shadow">
                      <img src="{{ $item['image'] }}" class="img-fluid">
                    </div>
                  </div>
<?php
    }
?>
                  <div class="col-{{ ($item['image'] !== null) ? '6' : '12' }}">
                    <div class="h4">{!! $item['title'] !!}</div>
                    <div class="h6 text-muted">{!! $item['date'] !!}</div>
                    <p>{!! $item['description'] !!}</p>
<?php
    if (isset($item['tags']) && is_array($item['tags'])) {
      echo '<div class="tags">';
      foreach ($item['tags'] as $tag) {
        echo '<span class="badge badge-secondary">' . htmlspecialchars($tag) . '</span> ';
      }
      echo '</div>';
    }
?>
                  </div>
                </div>
<?php 
  }
?>
<?php 
}
?>
        </div>
      </div>
    </div>
  </body>
</html>