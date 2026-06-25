@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
	<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php
	$errors = $errors ?? new \Illuminate\Support\ViewErrorBag();

	$loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
	$registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
	$passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

	if (config('adminlte.use_route_url', false)) {
		$loginUrl = $loginUrl ? route($loginUrl) : '';
		$registerUrl = $registerUrl ? route($registerUrl) : '';
		$passResetUrl = $passResetUrl ? route($passResetUrl) : '';
	} else {
		$loginUrl = $loginUrl ? url($loginUrl) : '';
		$registerUrl = $registerUrl ? url($registerUrl) : '';
		$passResetUrl = $passResetUrl ? url($passResetUrl) : '';
	}
@endphp

@section('css')
	<style>
		.login-page {
			min-height: 100vh;
			background:
				radial-gradient(circle at top left, rgba(10, 132, 255, 0.35), transparent 32%),
				radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.22), transparent 30%),
				linear-gradient(135deg, #06131f 0%, #0d2234 45%, #13324a 100%);
		}

		.login-box {
			width: 430px;
		}

		.login-logo a {
			color: #f8fafc;
			font-weight: 700;
			letter-spacing: 0.04em;
			text-transform: uppercase;
		}

		.login-logo img {
			display: none;
		}

		.login-card-body,
		.card-footer {
			background: transparent;
		}

		.card.card-outline.card-primary {
			border: 0;
			border-top: 4px solid #12b981;
			border-radius: 24px;
			box-shadow: 0 25px 70px rgba(2, 8, 23, 0.35);
			overflow: hidden;
		}

		.auth-shell {
			padding: 1.9rem 1.9rem 1.4rem;
			background: linear-gradient(180deg, rgba(248, 250, 252, 0.98), rgba(240, 249, 255, 0.96));
		}

		.auth-kicker {
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
			padding: 0.65rem 1.15rem;
			border-radius: 999px;
			background: rgba(16, 185, 129, 0.12);
			color: #047857;
			font-size: 1.05rem;
			font-weight: 700;
			letter-spacing: 0.1em;
			text-transform: uppercase;
		}

		.auth-heading {
			margin: 1rem 0 0.35rem;
			color: #0f172a;
			font-size: 1.8rem;
			font-weight: 700;
			line-height: 1.15;
		}

		.auth-copy {
			margin: 0 0 1.5rem;
			color: #475569;
			font-size: 0.98rem;
			line-height: 1.6;
		}

		.login-divider {
			height: 1px;
			margin: 1.2rem 0 1.4rem;
			background: linear-gradient(90deg, rgba(148, 163, 184, 0), rgba(148, 163, 184, 0.45), rgba(148, 163, 184, 0));
		}

		.input-group-text {
			background: #ecfeff;
			border-color: #cbd5e1;
			color: #0f766e;
		}

		.form-control {
			height: calc(2.8rem + 2px);
			border-color: #cbd5e1;
		}

		.form-control:focus {
			border-color: #14b8a6;
			box-shadow: 0 0 0 0.2rem rgba(20, 184, 166, 0.18);
		}

		.btn-login {
			height: 2.8rem;
			border: 0;
			border-radius: 0.85rem;
			background: linear-gradient(135deg, #0f766e, #14b8a6);
			box-shadow: 0 12px 24px rgba(15, 118, 110, 0.22);
			font-weight: 700;
		}

		.btn-login:hover {
			background: linear-gradient(135deg, #115e59, #0f766e);
		}

		.auth-footer-links a {
			color: #0f766e;
			font-weight: 600;
		}

		.auth-footer-links a:hover {
			color: #134e4a;
			text-decoration: none;
		}

		.auth-side-note {
			margin-top: 1rem;
			padding: 0.9rem 1rem;
			border-radius: 1rem;
			background: #0f172a;
			color: #cbd5e1;
			font-size: 0.92rem;
		}

		.auth-side-note strong {
			color: #f8fafc;
		}

		@media (max-width: 576px) {
			.login-box {
				width: calc(100% - 1.5rem);
			}

			.auth-shell {
				padding: 1.5rem 1.2rem 1.15rem;
			}
		}
	</style>
@stop

@section('auth_header')
	<div class="auth-shell text-center">
		<span class="auth-kicker">AbiSystem ERP&CRM</span>
		<div class="login-divider"></div>
	</div>
@stop

@section('auth_body')
	<form action="{{ $loginUrl }}" method="post">
		@csrf

		<div class="input-group mb-3">
			<input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
				value="{{ old('email') }}" placeholder="Correo electrónico" autofocus>

			<div class="input-group-append">
				<div class="input-group-text">
					<span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
				</div>
			</div>

			@error('email')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
		</div>

		<div class="input-group mb-3">
			<input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
				placeholder="Contraseña">

			<div class="input-group-append">
				<div class="input-group-text">
					<span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
				</div>
			</div>

			@error('password')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
		</div>

		<div class="d-flex align-items-center justify-content-between mb-4">
			<div class="icheck-primary" title="Mantener la sesión activa en este equipo.">
				<input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
				<label for="remember">Recordarme</label>
			</div>

			@if($passResetUrl)
				<a href="{{ $passResetUrl }}" class="small font-weight-semibold">¿Olvidaste tu contraseña?</a>
			@endif
		</div>

		<button type="submit" class="btn btn-block btn-login text-white">
			<span class="fas fa-sign-in-alt mr-2"></span>
			Entrar al sistema
		</button>

		<div class="auth-side-note">
			<strong>Acceso empresarial.</strong> Usa las credenciales asignadas a tu perfil para continuar.
		</div>
	</form>
@stop

@section('auth_footer')
	<div class="auth-footer-links text-center py-2">
		@if($registerUrl)
			<p class="mb-1">¿Aún no tienes cuenta? <a href="{{ $registerUrl }}">Solicita o crea acceso</a></p>
		@endif
		<p class="mb-0 text-muted">AbiSystem centraliza la operación comercial y administrativa.</p>
	</div>
@stop