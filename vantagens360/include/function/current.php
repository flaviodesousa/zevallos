<?php
function current_frontend() {
	global $INI;
	$a = array(
			'/index.php' => 'Ofertas de Hoje',
			'/team/index.php' => 'Ultimas Ofertas',
			'/help/tour.php' => 'Tour',
			'/subscribe.php' => 'Newsletter',
			);
	if (abs(intval($INI['system']['forum']))) {
		unset($a['/subscribe.php']);
		$a['/forum/index.php'] = 'Forum';
	}
	$r = $_SERVER['REQUEST_URI'];
	if (preg_match('#/team#',$r)) $l = '/team/index.php';
	elseif (preg_match('#/help#',$r)) $l = '/help/tour.php';
	elseif (preg_match('#/subscribe#',$r)) $l = '/subscribe.php';
	else $l = '/index.php';
	return current_link(null, $a);
}

function current_backend() {
	global $INI;
	$a = array(
			'/manage/misc/index.php' => 'Inicio',
			'/manage/team/index.php' => 'Clientes',
			'/manage/order/index.php' => 'Compras',
			'/manage/coupon/index.php' => $INI['system']['couponname'],
			'/manage/user/index.php' => 'Usuários',
			'/manage/partner/index.php' => 'Parceiros',
			'/manage/market/index.php' => 'Marketing',
			'/manage/category/index.php' => 'Categoria',
			'/manage/system/index.php' => 'Configurações',
			);
	$r = $_SERVER['REQUEST_URI'];
	if (preg_match('#/manage/(\w+)/#',$r, $m)) {
		$l = "/manage/{$m[1]}/index.php";
	} else $l = '/manage/misc/index.php';
	return current_link($l, $a);
}

function current_biz() {
	global $INI;
	$a = array(
			'/biz/index.php' => 'Inicio',
			'/biz/settings.php' => 'Informações',
			'/biz/coupon.php' => $INI['system']['couponname'] . '列表',
			);
	$r = $_SERVER['REQUEST_URI'];
	if (preg_match('#/biz/coupon#',$r)) $l = '/biz/coupon.php';
	elseif (preg_match('#/biz/settings#',$r)) $l = '/biz/settings.php';
	else $l = '/biz/index.php';
	return current_link($l, $a);
}

function current_forum($selector='index') {
	global $city;
	$a = array(
			'/forum/index.php' => 'Todos',
			'/forum/city.php' => "{$city['name']}Forum",
			'/forum/public.php' => 'Discuções',
			);
	if (!$city) unset($a['/forum/city.php']);
	$l = "/forum/{$selector}.php";
	return current_link($l, $a, true);
}

function current_city($cename, $citys) {
	$link = "/city.php?ename={$cename}";
	$links = array();
	foreach($citys AS $city) {
		$links["/city.php?ename={$city['ename']}"] = $city['name'];
	}
	return current_link($link, $links);
}

function current_coupon_sub($selector='index') {
	$selector = $selector ? $selector : 'index';
	$a = array(
		'/coupon/index.php' => 'Fora de Uso',
		'/coupon/consume.php' => 'Ultilizados',
		'/coupon/expire.php' => 'Vencido',
	);
	$l = "/coupon/{$selector}.php";
	return current_link($l, $a);
}

function current_account($selector='/account/settings.php') {
	global $INI;
	$a = array(
		'/order/index.php' => 'Minhas Compras',
		'/coupon/index.php' => 'Meu ' . $INI['system']['couponname'],
		'/credit/index.php' => 'Balanço',
		'/account/settings.php' => 'Configurações',
	);
	return current_link($selector, $a, true);
}

function current_about($selector='us') {
	global $INI;
	$a = array(
		'/about/us.php' => 'Sobre Nós' . $INI['system']['abbreviation'],
		'/about/contact.php' => 'Contato',
		'/about/job.php' => 'Trabalhos',
		'/about/privacy.php' => 'Política de Privacidade',
		'/about/terms.php' => 'Termos de Serviço',
	);
	$l = "/about/{$selector}.php";
	return current_link($l, $a, true);
}

function current_help($selector='faqs') {
	global $INI;
	$a = array(
		'/help/tour.php' => 'Como Funciona?',
		'/help/faqs.php' => 'FAQ',
		'/help/zuitu.php' =>'Sobre Nós',
	);
	$l = "/help/{$selector}.php";
	return current_link($l, $a, true);
}

function current_order_index($selector='index') {
	$selector = $selector ? $selector : 'index';
	$a = array(
		'/order/index.php?s=index' => 'Todos',
		'/order/index.php?s=unpay' => 'Não Pago',
		'/order/index.php?s=pay' => 'Pago',
	);
	$l = "/order/index.php?s={$selector}";
	return current_link($l, $a);
}

function current_link($link, $links, $span=false) {
	$html = '';
	$span = $span ? '<span></span>' : '';
	foreach($links AS $l=>$n) {
		if (trim($l,'/')==trim($link,'/')) {
			$html .= "<li class=\"current\"><a href=\"{$l}\">{$n}</a>{$span}</li>";
		}
		else $html .= "<li><a href=\"{$l}\">{$n}</a>{$span}</li>";
	}
	return $html;
}

/* manage current */
function mcurrent_misc($selector=null) {
	$a = array(
		'/manage/misc/index.php' => 'Inicio',
		'/manage/misc/ask.php' => 'Clientes',
		'/manage/misc/feedback.php' => 'Feedback',
		'/manage/misc/subscribe.php' => 'Newsletter',
		'/manage/misc/invite.php' => 'Peça Desconto',
		'/manage/misc/money.php' => 'Financias',
	);
	$l = "/manage/misc/{$selector}.php";
	return current_link($l,$a,true);
}

function mcurrent_misc_money($selector=null){
	$selector = $selector ? $selector : 'store';
	$a = array(
		'/manage/misc/money.php?s=store' => 'Top',
		'/manage/misc/money.php?s=charge' => 'Recarga Online',
		'/manage/misc/money.php?s=withdraw' => 'Desfazer',
		'/manage/misc/money.php?s=cash' => 'Dinheiro',
		'/manage/misc/money.php?s=refund' => 'Reenbolso',
	);
	$l = "/manage/misc/money.php?s={$selector}";
	return current_link($l, $a);
}

function mcurrent_misc_invite($selector=null){
	$selector = $selector ? $selector : 'index';
	$a = array(
		'/manage/misc/invite.php?s=index' => 'Convites',
		'/manage/misc/invite.php?s=record' => 'Desconto',
	);
	$l = "/manage/misc/invite.php?s={$selector}";
	return current_link($l, $a);
}
function mcurrent_order($selector=null) {
	$a = array(
		'/manage/order/index.php' => 'Ordens',
		'/manage/order/pay.php' => 'Pagamentos',
		'/manage/order/unpay.php' => 'Pendentes',
	);
	$l = "/manage/order/{$selector}.php";
	return current_link($l,$a,true);
}
function mcurrent_user($selector=null) {
	$a = array(
		'/manage/user/index.php' => 'Lista de Usuários',
		'/manage/user/manager.php' => 'Gerenciar lista',
	);
	$l = "/manage/user/{$selector}.php";
	return current_link($l,$a,true);
}
function mcurrent_team($selector=null) {
	$a = array(
		'/manage/team/index.php' => 'Clientes atuais',
		'/manage/team/success.php' => 'Sucesso',
		'/manage/team/failure.php' => 'Falha ao Comprar',
		'/manage/team/create.php' => 'Novos Clientes',
	);
	$l = "/manage/team/{$selector}.php";
	return current_link($l,$a,true);
}

function mcurrent_feedback($selector=null) {
	$a = array(
		'/manage/feedback/index.php' => 'Resumo',
	);
	$l = "/manage/feedback/{$selector}.php";
	return current_link($l,$a,true);
}
function mcurrent_coupon($selector=null) {
	$a = array(
		'/manage/coupon/index.php' => 'Não gastos',
		'/manage/coupon/consume.php' => 'Gastos',
		'/manage/coupon/expire.php' => 'Vencido',
		'/manage/coupon/card.php' => 'Cupons',
		'/manage/coupon/cardcreate.php' => 'Criar Cupom',
	);
	$l = "/manage/coupon/{$selector}.php";
	return current_link($l,$a,true);
}
function mcurrent_category($selector=null) {
	$zones = get_zones();
	$a = array();
	foreach( $zones AS $z=>$o ) {
		$a['/manage/category/index.php?zone='.$z] = $o;
	}
	$l = "/manage/category/index.php?zone={$selector}";
	return current_link($l,$a,true);
}
function mcurrent_partner($selector=null) {
	$a = array(
		'/manage/partner/index.php' => 'Empresas',
		'/manage/partner/create.php' => 'Incluir',
	);
	$l = "/manage/partner/{$selector}.php";
	return current_link($l,$a,true);
}
function mcurrent_market($selector=null) {
	$a = array(
		'/manage/market/index.php' => 'Email Marketing',
		'/manage/market/sms.php' => 'SMS',
		'/manage/market/down.php' => 'Baixar Dados',
	);
	$l = "/manage/market/{$selector}.php";
	return current_link($l,$a,true);
}
function mcurrent_market_down($selector=null) {
	$a = array(
		'/manage/market/down.php' => 'Telefone',
		'/manage/market/downemail.php' => 'E-mail',
		'/manage/market/downorder.php' => 'Ordens de Compra',
		'/manage/market/downcoupon.php' => 'Compras de Cupom',
		'/manage/market/downuser.php' => 'Info do Usuario',
	);
	$l = "/manage/market/{$selector}.php";
	return current_link($l,$a,true);
}

function mcurrent_system($selector=null) {
	$a = array(
		'/manage/system/index.php' => 'Basico',
		'/manage/system/bulletin.php' => 'Anuncios',
		'/manage/system/pay.php' => 'Pagamentos',
		'/manage/system/email.php' => 'Email',
		'/manage/system/sms.php' => 'SMS',
		'/manage/system/city.php' => 'Cidades',
		'/manage/system/page.php' => 'Paginas',
		'/manage/system/cache.php' => 'Cache',
		'/manage/system/skin.php' => 'Temas',
		'/manage/system/template.php' => 'Template',
		'/manage/system/upgrade.php' => 'Upgrade',
	);
	$l = "/manage/system/{$selector}.php";
	return current_link($l,$a,true);
}
