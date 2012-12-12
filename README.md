Så klonar du ramverket från GitHub och installerar det på studentservern 
------------------------------------------------------------------------ 
  
1.	Starta Git i kommandoprompten och skriv följande:

       git clone git://github.com/hermanbth/maveric.git
	   
2.	Anslut till studentservern och lägg upp den nedladdade maveric-katalogen i www-katalogen (rotnivån).

3.	Gör data-katalogen (maveric/site/data) skrivbar genom att sätta filrättigheterna till 777. 

4.	I webbläsaren skriver du in följande och byter ut användarnamn mot ditt användarnamn:

	   http://www.student.bth.se/~användarnamn/maveric/module/install

	Du ska komma till en sida som bekräftar om installeringen lyckats. 






Så ändrar du logo
------------------------------------------------------------------------ 

1.	Den bild som du vill använda som logotyp laddar du upp på servern i katalogen site/themes/mytheme/


2.	I site/config.php ändrar du värdet hos nyckeln logo i config-theme-arrayen
 
				$ma->config['theme'] = array(
				   // kod här
				'logo' => 'logo_80x80.png',
		
    till filnamnet hos din logotyp.
	
	
3.	Om du vill ändra bredden och höjden på logotypen ändrar du de numeriska värdena (pixlar) 
    hos 'logo_width' => och 'logo_height' => som ligger i koden nedanför nyckeln logo.


	

Så ändrar du titel på sidan 
------------------------------------------------------------------------ 

1.	I  site/src/CCMycontroller/CCMycontroller.php går du till klassen CCMycontroller och letar upp 
		den metod som motsvarar sidan vars titel du vill ändra. T ex motsvarar sidan My Blog metoden Blog().  

2.	I metoden ändrar du strängen för argumentet i SetTitle()till önskad sidtitel: 

				$this->views->SetTitle('Titelns namn')



		
Så ändrar du footer 
------------------------------------------------------------------------ 

Följande beskriver hur du ändrar copyright-meddelandet i footern.
 
I  site/config.php letar du reda på $ma->config['theme']. Nyckeln footer har ett värde 
vars innehåll du ändrar.

				$ma->config['theme'] = array(
				  // kod
				  ),
				  // kod
				  'data' => array(
						//kod
						'footer' => '<p>Maveric &copy;</p>',
					),
				);


		

Så ändrar du navigeringsmeny
------------------------------------------------------------------------ 

1.	I  site/config.php ändrar du i $ma->config['menus'] i den array som ligger på nyckeln 'my-navbar'. 
		Om du t ex vill ändra menyalternativet ’Guestbook’ till ’My little guestbook’ byter du ut värdet för 
		nyckeln label. 

				'guestbook' => array('label'=>'My little guestbook', 'url'=>'my/guestbook'),

		
2.	Om du vill ändra vilken sida som länken leder till ändrar du värdet hos nyckeln url. Tänk på att 
		det måste finnas någon motsvarande metod i controllern site/src/CCMycontroller/CCMycontroller.php
		som skapar sidan.  



	
Skapa en blog
------------------------------------------------------------------------  
1.	I site/config.php i $ma->config['menus'] på nyckeln my-navbar lägger du in 

				'myBlog'      => array('label'=>'My Blog', 'url'=>'my/myblog'),

		
2.	Lägg in metoden MyBlog() i site/src/CCMycontroller/CCMycontroller.php inuti klassen CCMycontroller

				public function MyBlog() {
					$content = new CMContent();
					$this->views->SetTitle('My blog')
								->AddInclude(__DIR__ . '/myblog.php', array(
								   'contents' => $content->ListAll(array(
								   'type'=>'post', 
								   'order-by'=>'title', 
								   'order-order'=>'DESC')),
					 ));
				}

		
3.	Skapa ett nytt dokument site/src/CCMycontroller/myblog.php och lägg in följande kod däri

				<h1>My Blog</h1>
				<p>All nice news and blogposts about me.</p>

				<?php if($contents != null):?>
				  <?php foreach($contents as $val):?>
					<h2><?=esc($val['title'])?></h2>
					<p class='smaller-text'><em>Posted on <?=$val['created']?> 
				by <?=$val['owner']?></em></p>
					<p><?=filter_data($val['data'], $val['filter'])?></p>
				<p class='smaller-text silent'><a   href='<?=create_url("content/edit/{$val['id']}")?>'>edit</a></p>
				 <?php endforeach; ?>
				<?php else:?>
				 <p>No posts exists.</p>
				<?php endif;?>



	   
Skapa en sida 
------------------------------------------------------------------------ 

1.	I site/config.php i $ma->config['menus'] på nyckeln my-navbar lägger du in 

				'myPage'      => array('label'=>'My Page', 'url'=>'my/myPage'),
		
		Se till så att nyckeln (i exemplet ’page’) inte redan finns i arrayen my-navbar, om så är fallet 
		ge nyckeln ett unikt namn.
	
	
2.	Lägg in metoden MyPage() i site/src/CCMycontroller/CCMycontroller.php inuti klassen CCMycontroller

				public function MyPage() {
				 $content = new CMContent(5);
				 $this->views->SetTitle('My page')
							 ->AddInclude(__DIR__ . '/myPage.php', array(
							   'content' => $content,
							 ));
			   }

	   
3.	Skapa ett nytt dokument  site/src/CCMycontroller/myPage.php och lägg in följande kod däri

				<?php if($content['id']):?>
				  <h1><?=esc($content['title'])?></h1>
				  <p><?=$content->GetFilteredData()?></p>
				  <p class='smaller-text silent'><a href='<?=create_url("content/edit/{$content['id']}")?>'>edit</a> <a href='<?=create_url("content")?>'>view all</a></p>
				<?php else:?>
				  <p>404: No such page exists.</p>
				<?php endif;?>
			
