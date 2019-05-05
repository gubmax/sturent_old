<form class="page__block page__block_narrow form" role="form" action="{{ route('login') }}" method="post">
	{{ csrf_field() }}

	<p class="form-text_center">Войти через социальные сети</p>

	<div class="social-block">
		<a class="social-block__btn btn_with-icon btn_social_vk" href="{{ url('auth/vk') }}" title="Вконтакте" v-ripple>
			<i class="icon">
				<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
					<path d="m0.082828,5.354315c-0.163359,-0.996839 4.655725,-0.379655 4.655725,-0.379655c0,0 2.797519,7.025341 3.900191,7.025341c1.102672,0 0.571756,-5.957299 0.571756,-5.957299c0,0 -1.36813,-0.356014 -1.36813,-0.901902c0,-0.545888 1.36813,-0.925636 1.36813,-0.925636c0,0 3.540627,0 4.02271,0c0.482083,0 0.673934,0.783231 0.673935,1.305384c0,0.522154 0,6.384516 0,6.384516l1.102672,0c0,0 0.981556,-1.577636 1.756027,-3.180391c0.774471,-1.602755 1.715267,-3.750013 1.715267,-3.750013l4.676145,0c0,0 0.857634,0.023549 0.755534,0.735669c-0.138897,0.968781 -3.249906,5.569356 -3.900191,7.144012c-0.490076,1.186713 2.058765,2.792936 3.144656,4.509509c0.704759,1.114079 0.755534,2.420987 0.755534,2.420987l-5.554119,0l-2.777179,-3.227952c0,0 -0.571676,-0.23725 -1.020992,0.094937c-0.449316,0.332187 -0.469657,0.996839 -0.469657,0.996839l0,2.136176l-4.349427,0c0,0 -3.328435,-1.139244 -6.146374,-6.123439c-2.817939,-4.984194 -3.348855,-7.310245 -3.512214,-8.307084z"/>
				</svg>
			</i>
		</a>
		<a class="social-block__btn btn_with-icon btn_social_facebook" href="{{ url('auth/facebook') }}" title="Facebook" v-ripple>
			<i class="icon">
				<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
					<path d="m17.584148,7.840448l-3.735876,0l0,-2.450187c0,-0.920164 0.609859,-1.13469 1.039399,-1.13469c0.428563,0 2.63637,0 2.63637,0l0,-4.045203l-3.630812,-0.014171c-4.030543,0 -4.947775,3.017043 -4.947775,4.947775l0,2.696476l-2.330952,0l0,4.168348l2.330952,0c0,5.349461 0,11.795007 0,11.795007l4.902818,0c0,0 0,-6.509073 0,-11.795007l3.30829,0l0.427586,-4.168348z"/>
				</svg>
			</i>
		</a>
		<a class="social-block__btn btn_with-icon btn_social_google" href="{{ url('auth/google') }}" title="Google+" v-ripple>
			<i class="icon">
				<svg class="sr-icon sr-icon_light" viewBox="0 0 24 24">
					<path d="m8.109334,19.979622c-4.480065,0 -8.124668,-3.5792 -8.124668,-7.979094c0,-4.399895 3.644602,-7.980149 8.124668,-7.980149c1.958908,0 3.852413,0.695164 5.329242,1.956798l-2.066505,2.332334c-0.90403,-0.77217 -2.063341,-1.198341 -3.263791,-1.198341c-2.744792,0 -4.977968,2.193091 -4.977968,4.888303c0,2.695212 2.233176,4.888303 4.977968,4.888303c2.321786,0 3.841864,-1.112896 4.335547,-3.108724l-4.297572,0l0,-3.090791l7.619381,0l0,1.545396c0.001055,4.631968 -3.076023,7.745967 -7.656302,7.745967z"/>
					<polygon points="24.076580047607422,10.731418240815401 21.6633358001709,10.731418240815401 21.6633358001709,8.31925355270505 19.73404312133789,8.31925355270505 19.73404312133789,10.731418240815401 17.32187271118164,10.731418240815401 17.32187271118164,12.660717595368624 19.73404312133789,12.660717595368624 19.73404312133789,15.072879422456026 21.6633358001709,15.072879422456026 21.6633358001709,12.660717595368624 24.076580047607422,12.660717595368624"/>
				</svg>
			</i>
		</a>
	</div>

	<p class="form-text_center">Или через E-mail</p>

	<div class="form-group_field{{ $errors->has('email') ? ' has-error' : '' }}">
		<input id="email" type="email" name="email" value="{{ old('email') }}" required v-field>
		<label for="email">E-Mail адрес</label>
		@if ($errors->has('email'))
				<span class="msg-error">{{ $errors->first('email') }}</span>
		@endif
	</div>

	<div class="form-group_field{{ $errors->has('password') ? ' has-error' : '' }}">
		<input id="password" type="password" name="password" required v-field>
		<label for="password">Пароль</label>
		@if ($errors->has('password'))
				<span class="msg-error">{{ $errors->first('password') }}</span>
		@endif
		<span class="form-text_right">Забыли <a class="form-text_link" href="{{ route('password.request') }}">пароль</a>?</span>
	</div>

	<div class="btns-group">
		<button type="submit" class="btn btn_primary" v-ripple>Войти</button>
		<span class="form-text">Ещё нет аккаунта? <a class="form-text_link" href="{{ route('register') }}">Зарегистироваться</a></span>
	</div>
</form>
