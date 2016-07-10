<ul class="menu">
	<?php
	if($loged_in || ($settings['allow_anonymous_shopping']===true)) {
		?>
		<li><a href="/Retail">Handla</a></li>
		<?php
	}
	?>
	<li><a href="/Product/price_list">Prislista</a></li>
	<?php if($loged_in): ?>
		<li class="dir">
			<a href="/Product/stock">Lager</a>
			<ul>
				<li class="dir">
					<a href="/Category">Kategorier</a>
					<ul>
						<?php foreach($categories as $category): ?>
							<li><a href="/Category/view/<?=$category->id?>"><?=$category?></a></li>
						<?php endforeach ?>
					</ul>
				</li>
				<li><a href="/Delivery/create">Ny leverans</a></li>
				<li><a href="/Delivery/index">Leveranser</a></li>
				<li><a href="/Product/take_stock">Inventering</a></li>
				<li><a href="/Product/log">Prislogg</a></li>
			</ul>
		</li>
		<li class="dir">
			<a href="/Account">Bokföring</a>
			<ul>
				<li class="dir">
					<a href="/Account">Konton</a>
					<ul>
						<?php foreach($accounts as $account): ?>
							<li title="<?=$account->description?>">
								<a href="/Account/view/<?=$account->code_name?>">
									<?=$account?>
								</a>
							</li>
						<?php endforeach ?>
					</ul>
				</li>
				<li><a href="/DailyCount">Dagsavslut</a></li>
				<li><a href="/Transaction/create">Ny transaktion</a></li>
				<li><a href="/Transaction/index">Verifikationslista</a></li>
				<li><a href="/Retail/log">Transaktionslogg</a></li>
				<li class="dir">
					<a href="/Report">Rapporter</a>
					<ul>
						<li><a href="/Report/result">Resultatrapport</a></li>
						<li><a href="/Report/balance">Balansrapport</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<form action="/Session/logout" method="post">
				<div>
					<input type="submit" value="Logga ut" />
				</div>
			</form>
		</li>
	<?php elseif ($settings['primary_login_method'] == "nxauth"): ?>
		<li>
			<a href="/Session/nx_login?kickback=<?=htmlspecialchars(kickback_url())?>">
				Logga in
			</a>
		</li>
		<li>
			<a href="/Session/login?kickback=<?=htmlspecialchars(kickback_url())?>">
				Lokal inloggning
			</a>
		</li>
	<?php else: ?>
		<li>
			<a href="/Session/login?kickback=<?=htmlspecialchars(kickback_url())?>">
				Logga in
			</a>
		</li>
	<?php endif ?>
</ul>
