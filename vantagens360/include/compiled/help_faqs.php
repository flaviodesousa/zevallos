<?php include template("header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="help">
	<div class="dashboard" id="dashboard">
		<ul><?php echo current_help('faqs'); ?></ul>
	</div>
	<div id="content" class="faq clear">
        <div class="box">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head"><h2>FAQ</h2></div>
                <div class="sect"><?php echo $page['value']; ?></div>
            </div>
            <div class="box-bottom"></div>
        </div>
	</div>
    <div id="sidebar">
        <div class="sbox-white">
            <div class="sbox-top"></div>
            <div class="sbox-content">
                <div class="side-tip-help">
                    <p>
                        <a href="/help/about.php"><img src="/static/img/faq-how-it-works1.gif"></a>
                        <span>A. Compre um cupom hoje!</span>
                    </p>
                    <p>
                        <a href="/help/about.php"><img src="/static/img/faq-how-it-works2.gif"></a>
                        <span>B. Voce pode...</span>
                    </p>
                    <p>
                        <a href="/help/about.php"><img src="/static/img/faq-how-it-works3.gif"></a>
                        <span>C. Volte aqui sempre!</span>
                    </p>
                </div>
            </div>
            <div class="sbox-bottom"></div>
        </div>
    </div>
</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->

<?php include template("footer");?>

