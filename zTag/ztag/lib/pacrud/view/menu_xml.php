<?php
Header('Content-type: application/xml; charset=UTF-8');
if (!isset($pacrudConfig['pacrudWebPath'])) {
	$pacrudConfig['pacrudWebPath'] = substr($_SERVER['REQUEST_URI'],0,strlen($_SERVER['REQUEST_URI'])-18);
}
?>
<pacrudMenu>
	<menu id="system" label="SYSTEM" opened="1">
		<level1 id="users" label="Users">
			<link>index.php?page=users</link>
			<icon>/pacrud/view/images/icons/users.png</icon>
		</level1>
		<level1 id="changePassword" label="Change Password">
			<link>index.php?page=changePassword</link>
			<icon>/pacrud/view/images/icons/key.png</icon>
		</level1>
		<level1 id="about" label="About">
			<link>index.php?page=about</link>
			<icon>/pacrud/view/images/icons/exclamation.png</icon>
		</level1>
	</menu>
	<menu id="register" label="REGISTERS" opened="1">
		<level1 id="peoples" label="Peoples">
			<level2 id="juridic_people" label="Juridic People">
				<link>index.php?page=juridic_people</link>
				<icon>/pacrud/view/images/icons/users.png</icon>
			</level2>
			<level2 id="fisic_people" label="Fisic People">
				<link>index.php?page=pessoa_fisica</link>
				<icon>/pacrud/view/images/icons/users.png</icon>
			</level2>
			<icon>/pacrud/view/images/icons/users.png</icon>
		</level1>
		<level1 id="places" label="Places">
			<link>index.php?page=places</link>
			<icon>/pacrud/view/images/icons/example.png</icon>
		</level1>
		<level1 id="things" label="Things">
			<link>index.php?page=things</link>
			<icon>/pacrud/view/images/icons/example.png</icon>
		</level1>
		<icon>/pacrud/view/images/icons/example.png</icon>
	</menu>
	<menu id="direct" label="DIRECT">
		<link>index.php?page=direct</link>
		<icon>/pacrud/view/images/icons/example.png</icon>
	</menu>
	<menu id="iconless" label="ICON LESS MENU">
		<level1 id="iconless_menu_level1" label="Example level 1">
			<link>index.php</link>
		</level1>
	</menu>
	<menu id="exampla_menu" label="EXAMPLE MENU">
		<level1 id="example_menu_level1" label="Example level 1">
			<level2 id="example_menu_level2" label="Example level 2">
				<level3 id="example_menu_level3" label="Example level 3">
					<level4 id="example_menu_level4" label="Example level 4">
						<level5 id="example_menu_level5" label="Example level 5">
							<link>index.php?page=example_menu_level5</link>
							<icon>/pacrud/view/images/icons/example.png</icon>
						</level5>
						<icon>/pacrud/view/images/icons/example.png</icon>
					</level4>
					<icon>/pacrud/view/images/icons/example.png</icon>
				</level3>
				<icon>/pacrud/view/images/icons/example.png</icon>
			</level2>
			<icon>/pacrud/view/images/icons/example.png</icon>
		</level1>
		<icon>/pacrud/view/images/icons/example.png</icon>
	</menu>
</pacrudMenu>
