<a href="{{ route('password.reset', ['token' => $token]) }}">
  パスワード再設定リンク<br>
  トークン：{{ $token }}
</a>