function JSRTS(Settings){
	var Settings = Settings;
	var Players = new Array();
	var Weapons = new Object();
	//var Units = new Array();
	var UnitTypes = new Object();
	this.StartGame = function(){
		Settings.GameArea.css({'width':Settings.width,'height':Settings.height,'position':'relative'});
		Settings.GameArea.addClass(Settings.class);
		Settings.GameArea.click(function(event){
			var Left = event.pageX - Settings.GameArea.offset().left + Settings.GameArea.scrollLeft();
			var Top = event.pageY - Settings.GameArea.offset().top + Settings.GameArea.scrollTop();
			
			$('.Selected').each(function(){
				$(this).data().Unit.Move(event,Top,Left);
			});
		});
		
		Settings.StatusBar = $('<div></div');
		Settings.GameArea.after(Settings.StatusBar);
		Settings.StatusBar.addClass(Settings.statusClass);
		Settings.StatusBar.css({'width':Settings.width,'height':'80px'});
		
		Weapons['MachineGun'] = new Weapon({'attack':5,'range':100,'firerate':1000});
		
		UnitTypes['Soldier'] = {speed:0.1,class:'Soldier',life:100,defense:4,weapon:Weapons['MachineGun']};
	}
	this.addPlayer = function(Startup){
		var NewPlayer = new Player(Startup);
		Players.push(NewPlayer);
	}
	this.addUnit = function(Startup){
		var NewUnit = new Unit(Startup,UnitTypes[Startup.type]);
		//Units.push(NewUnit);
		NewUnit.UnitDiv.data({Unit:NewUnit});
	}
	var Player = function(Startup){
		this.Info = Startup;
	}
	var Weapon = function(Specs){
		for(var param in Specs){
			this[param] = Specs[param];
		}
	}
	var Unit = function(Startup,Specs){
		//alert(Units.length);
		for(var prop in Specs){
			this[prop] = Specs[prop];
		}
		for(var prop in Startup){
			this[prop] = Startup[prop];
		}
		this.UnitDiv = $('<div></div>');
		this.UnitDiv.addClass('Unit');
		this.UnitDiv.addClass(this.class);
		this.attackTimeouts = new Array();
		Settings.GameArea.append(this.UnitDiv);
		//this.UnitDiv.data({UnitArrayIndex:Units.length});
		this.SetPosition(Startup.startTop,Startup.startLeft);
		
		if(this.player == 0){
			this.UnitDiv.click(function(event){
				
				Settings.StatusBar.html('');
				
				if($(this).hasClass('Selected')){
					if(!event.ctrlKey){
						$('.Selected').removeClass('Selected');
						$(this).addClass('Selected');
					}else{
						$(this).removeClass('Selected');
					}
				}else{
					if(!event.ctrlKey){
						$('.Selected').removeClass('Selected');
					}
					$(this).addClass('Selected');
				}
				$('.Selected').each(function(){
					var UnitSpecs = $('<div></div>');
					UnitSpecs.css({width:'100px',display:'inline-block'});
					var DisplayProps = ['speed','type'];
					for(var prop in DisplayProps){
						UnitSpecs.append('<div>' + DisplayProps[prop] + ':' + $(this).data().Unit[DisplayProps[prop]] + '</div>');
					}
					Settings.StatusBar.append(UnitSpecs);
				});
				event.stopPropagation();
			});
		}else{
			this.UnitDiv.click(function(event){
				var EnemyUnit = $(this);
				$('.Selected').each(function(){
					$(this).data().Unit.Attack(event,EnemyUnit);
				});
				//alert('Enemy');
				event.stopPropagation();
			});
			/*var that = this;
			for(var i = 0;i<10;i++){
				var top = Math.round(Math.random() * 1000);
				var left = Math.round(Math.random() * 1000);
				//that.Move({'ctrlKey':true},top,left);
				setTimeout(function(){that.Move({'ctrlKey':true},top,left);},500);
			}*/
		}
		
	}
	
	Unit.prototype = {
		Move:function(event,top,left){
			this.ActionRequested();
			//var coords = this.CheckForCollision(top,left);
			//var top = coords.top;
			//var left = coords.left;
			if(!event.ctrlKey){
				this.UnitDiv.clearQueue();
			}
			this.UnitDiv.queue(function(next){
				var UnitDiv = $(this);
				//var Dist = Math.sqrt(Math.pow(Math.abs(UnitDiv.position().left - left),2) + Math.pow(Math.abs(UnitDiv.position().top - top),2));
				var Dist = UnitDiv.data().Unit.Distance(UnitDiv.position().top,UnitDiv.position().left,top,left);
				UnitDiv.animate({top:top + 'px',left:left + 'px'},{'duration':Dist/UnitDiv.data().Unit.speed,'easing':'linear','complete':function(){
					var Unit = $(this).data().Unit;
					var coords = Unit.CheckForCollision($(this).position().top,$(this).position().left);
					if(coords.top != $(this).position().top || coords.left != $(this).position().left){
						Unit.Move({ctrlKey:true},coords.top,coords.left);
					}
					//console.log(currentValue);
					//$(this).data().Unit.
				}});
				next();
				//UnitDiv.dequeue();
			});
		},
		SetPosition:function(top,left){
			this.ActionRequested();
			var coords = this.CheckForCollision(top,left);
			var NewTop = coords.top;
			var NewLeft = coords.left;
			this.UnitDiv.css({top:NewTop + 'px',left:NewLeft + 'px'});
		},
		Attack:function(event,EnemyUnit){
			this.ActionRequested();
			this.UnitDiv.css({'background-color':'#000'});
			if(!event.ctrlKey){
				this.UnitDiv.clearQueue();
			}
			this.UnitDiv.queue(function(next){
				var UnitDiv = $(this);
				var DistanceToEnemy = UnitDiv.data().Unit.Distance(EnemyUnit.position().top,EnemyUnit.position().left,UnitDiv.position().top,UnitDiv.position().left);
				//alert(DistanceToEnemy);
				//alert(UnitDiv.data().Unit.Specs.weapon.Specs.range);
				if(DistanceToEnemy <= UnitDiv.data().Unit.weapon.range){
					EnemyUnit.data().Unit.life-= Math.max(UnitDiv.data().Unit.weapon.attack - EnemyUnit.data().Unit.defense,1);
					EnemyUnit.html(EnemyUnit.data().Unit.life);
					if(EnemyUnit.data().Unit.life > 0){
						UnitDiv.data().Unit.attackTimeouts.push(setTimeout(function(){
							UnitDiv.data().Unit.Attack({'ctrlKey':true},EnemyUnit);
						},UnitDiv.data().Unit.weapon.firerate));
					}else{
						setTimeout(function(){
							EnemyUnit.remove();
						},1000);
					}
				}else{
					var MoveDistance = DistanceToEnemy - UnitDiv.data().Unit.weapon.range + 1;
					var NewTop = UnitDiv.position().top + ((EnemyUnit.position().top-UnitDiv.position().top) * MoveDistance/DistanceToEnemy);
					var NewLeft = UnitDiv.position().left + ((EnemyUnit.position().left-UnitDiv.position().left) * MoveDistance/DistanceToEnemy);
					UnitDiv.data().Unit.Move({'ctrlKey':true},Math.round(NewTop),Math.round(NewLeft));
					UnitDiv.data().Unit.Attack({'ctrlKey':true},EnemyUnit);
				}
				//UnitDiv.dequeue();
				next();
			});
		},
		Distance:function(top1,left1,top2,left2){
			var Dist = Math.sqrt(Math.pow(Math.abs(left1 - left2),2) + Math.pow(Math.abs(top1 - top2),2));
			return Dist;
		},
		ActionRequested:function(){
			for(var timeout in this.attackTimeouts){
				//alert(timeout);
				clearTimeout(this.attackTimeouts[timeout]);
			}
			this.attackTimeouts = new Array();
		},
		CheckForCollision:function(top,left){
			var NewTop = top;
			var NewLeft = left;
			var UnitDiv = this.UnitDiv;
			//alert(this.UnitDiv[0]);
			Settings.GameArea.children('.Unit').not(this.UnitDiv[0]).each(function(){
				var Child = $(this);
				var TopLeftIn = top >= Child.position().top && top <= Child.position().top + Child.height() && left >= Child.position().left && left <= Child.position().left + Child.width();
				var TopRightIn = top >= Child.position().top && top <= Child.position().top + Child.height() && left + UnitDiv.width() >= Child.position().left && left + UnitDiv.width() <= Child.position().left + Child.width();
				var BottomLeftIn = top + UnitDiv.height() >= Child.position().top && top + UnitDiv.height() <= Child.position().top + Child.height() && left >= Child.position().left && left <= Child.position().left + Child.width();
				var BottomRightIn = top + UnitDiv.height() >= Child.position().top && top + UnitDiv.height() <= Child.position().top + Child.height() && left + UnitDiv.width() >= Child.position().left && left + UnitDiv.width() <= Child.position().left + Child.width();
				if(TopLeftIn || TopRightIn || BottomLeftIn || BottomRightIn){
					var randnum = Math.round(Math.random() * 8);
					switch(randnum){
						case 0:
							NewTop = Math.max(Child.position().top - Child.height() - 1,0);
							NewLeft = Math.max(Child.position().left - Child.width() - 1,0);
						break;
						case 1:
							NewTop = Math.max(Child.position().top - Child.height() - 1,0);
							NewLeft = Math.max(Child.position().left,0);
						break;
						case 2:
							NewTop = Child.position().top - Child.height() - 1;
							NewLeft = Child.position().left + Child.width() + 1;
						break;
						case 3:
							NewTop = Child.position().top;
							NewLeft = Child.position().left + Child.width() + 1;
						break;
						case 4:
							NewTop = Child.position().top + Child.height() + 1;
							NewLeft = Child.position().left + Child.width() + 1;
						break;
						case 5:
							NewTop = Child.position().top + Child.height() + 1;
							NewLeft = Child.position().left;
						break;
						case 6:
							NewTop = Child.position().top + Child.height() + 1;
							NewLeft = Child.position().left - Child.width() - 1;
						break;
						case 7:
							NewTop = Child.position().top;
							NewLeft = Math.max(Child.position().left - Child.width() - 1,0);
						break;
					}
				}
			});
			//alert(NewTop);
			return {top:NewTop,left:NewLeft};
		}
	};
}