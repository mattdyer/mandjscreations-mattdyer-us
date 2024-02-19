function JSRTS(Settings){
	var Settings = Settings;
	var Players = new Array();
	var Weapons = new Object();
	//var Units = new Array();
	var UnitTypes = new Object();
	this.ShowGameForm = function(){
		Settings.GameArea.css({'width':Settings.width,'height':Settings.height,'position':'relative'});
		Settings.GameArea.addClass(Settings.class);
		
		$.post('ajax/index.php',{'Action':'GetGames'},function(data){
			var JoinForm = $('#JoinGameForm');
			if(data.length > 0){
				JoinForm.html('');
				JoinForm.append('<h3>Join a Game</h3>');
				JoinForm.append('<div>Player Name:<br /><input type="text" name="JoinPlayerName" id="JoinPlayerName"></div>');
				var GameSelect = $('<select name="JoinGameSelect" id="JoinGameSelect"></select>');
				JoinForm.append(GameSelect);
				var NewOption = $('<option></option>');
				for(var i in data){
					NewOption = $('<option value="' + data[i].GameID + '">' + data[i].Name + '</option>');
					GameSelect.append(NewOption);
				}
				var JoinButton = $('<button type="button" id="JoinGameButton">Go</button>');
				JoinButton.click(function(){
					Settings.GameID = GameSelect.attr('value');
					Settings.GameName = GameSelect.text();
					var PlayerName = $('#JoinPlayerName').val();
					//alert(Settings.GameID);
					//alert(Settings.GameName);
					$.post('ajax/index.php',{'Action':'CreatePlayer','GameID':Settings.GameID,'Name':PlayerName},function(PlayerID){
						//alert(PlayerID);
						Settings.ActivePlayerID = PlayerID;
						that.addPlayer({'PlayerID':PlayerID,'Name':PlayerName});
						that.StartGame();
					});
				});
				JoinForm.append(JoinButton);
			}else{
				JoinForm.html('No Games Available');
			}
		});
		
		Settings.GameArea.append('<h3>Create a Game</h3>');
		Settings.GameArea.append('<div>Player Name:<br /><input type="text" name="PlayerName" id="PlayerName"></div>');
		Settings.GameArea.append('<div>Game Name:<br /><input type="text" name="GameName" id="GameName"></div>');
		Settings.GameArea.append('<div><button type="button" id="CreateGameButton">Go</button></div>');
		Settings.GameArea.append('<div id="JoinGameForm">Loading...</div>');
		$('#CreateGameButton').click(function(){
			//alert('CreateGame');
			var GameName = $('#GameName').val();
			//alert(GameName);
			$.post('ajax/index.php',{'Action':'CreateGame','Name':GameName,'Started':'No'},function(GameID){
				//alert(GameID);
				Settings.GameID = GameID;
				Settings.GameName = GameName;
				var PlayerName = $('#PlayerName').val();
				$.post('ajax/index.php',{'Action':'CreatePlayer','GameID':GameID,'Name':PlayerName},function(PlayerID){
					//alert(PlayerID);
					Settings.ActivePlayerID = PlayerID;
					that.addPlayer({'PlayerID':PlayerID,'Name':PlayerName});
					that.StartGame();
				});
			});
		});
	}
	this.StartGame = function(){
		Settings.GameArea.html('');
		Settings.GameArea.css({'width':Settings.width,'height':Settings.height,'position':'relative'});
		Settings.GameArea.addClass(Settings.class);
		Settings.GameArea.click(function(event){
			var Left = event.pageX - Settings.GameArea.offset().left + Settings.GameArea.scrollLeft();
			var Top = event.pageY - Settings.GameArea.offset().top + Settings.GameArea.scrollTop();
			
			$('.Selected').each(function(){
				$(this).data().Unit.Move('Queue',event,Top,Left);
			});
		});
		
		Settings.StatusBar = $('<div></div');
		Settings.GameArea.after(Settings.StatusBar);
		Settings.StatusBar.addClass(Settings.statusClass);
		Settings.StatusBar.css({'width':Settings.width,'height':'80px'});
		
		Weapons['MachineGun'] = new Weapon({'attack':10,'range':100,'firerate':1000});
		
		UnitTypes['Soldier'] = {speed:0.1,class:'Soldier',life:100,defense:4,weapon:Weapons['MachineGun']};
	}
	this.getActions = function(){
		$.post('ajax/index.php',{'Action':'GetActions','PlayerID':Settings.ActivePlayerID},function(Actions){
			for(var i in Actions){
				var Action = Actions[i];
				switch(Action.Type){
					case 'AddUnit':
						that.addUnit('Execute',Action.Data);
					break;
					case 'MoveUnit':
						//alert(Action.Data.UnitID);
						$('#Unit_' + Action.Data.UnitID).data().Unit.Move('Execute',Action.Data.event,Action.Data.top,Action.Data.left);
					break;
					case 'AttackUnit':
						var EnemyUnit = $('#Unit_' + Action.Data.EnemyUnitID);
						if(EnemyUnit.length > 0){
							$('#Unit_' + Action.Data.UnitID).data().Unit.Attack('Execute',Action.Data.event,EnemyUnit);
						}
					break;
				}
				$.post('ajax/index.php',{'Action':'CompleteAction','ActionID':Action.ActionID,'PlayerID':Settings.ActivePlayerID},function(PlayerActionCompleteID){
					//alert(PlayerActionCompleteID);
				});
			}
			setTimeout(function(){that.getActions();},200);
		});
	}
	this.getActivePlayerID = function(){
		return Settings.ActivePlayerID;
	}
	this.addPlayer = function(Startup){
		var NewPlayer = new Player(Startup);
		Players.push(NewPlayer);
	}
	this.addUnit = function(Mode,Startup){
		if(Mode == 'Execute'){
			var NewUnit = new Unit(Startup,UnitTypes[Startup.type]);
			NewUnit.UnitID = Startup.UnitID;
			//Units.push(NewUnit);
			NewUnit.UnitDiv.data({Unit:NewUnit});
			NewUnit.UnitDiv.attr('id','Unit_' + Startup.UnitID);
		}else if(Mode == 'Queue'){
			$.post('ajax/index.php',{'Action':'CreateUnit','PlayerID':Startup.PlayerID,'Type':Startup.type},function(UnitID){
				Startup.UnitID = UnitID;
				$.post('ajax/index.php',{"Action":"AddAction","GameID":Settings.GameID,"Type":"AddUnit","Data":JSON.stringify(Startup)},function(ActionID){
					
				});
			});
		}
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
		
		if(this.PlayerID == Settings.ActivePlayerID){
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
					$(this).data().Unit.Attack('Queue',event,EnemyUnit);
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
		Move:function(Mode,event,top,left){
			if(Mode == 'Execute'){
				this.ActionRequested();
				//var coords = this.CheckForCollision(top,left);
				//var top = coords.top;
				//var left = coords.left;
				var FunctionToCall = arguments[4];
				var ArgumentsForFunction = arguments[5];
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
							Unit.Move.apply(Unit,['Queue',{ctrlKey:true},coords.top,coords.left,FunctionToCall,ArgumentsForFunction]);
						}
						//console.log(currentValue);
						//$(this).data().Unit.
					}});
					next();
					//UnitDiv.dequeue();
				});
				if(typeof arguments[4] != 'undefined'){
					//alert('called');
					arguments[4].apply(this,arguments[5] || []);
				}
			}else if(Mode == 'Queue'){
				//alert(this.UnitID);
				var Data = {"UnitID":this.UnitID,"event":{'ctrlKey':event.ctrlKey},"top":top,"left":left}
				$.post('ajax/index.php',{"Action":"AddAction","GameID":Settings.GameID,"Type":"MoveUnit","Data":JSON.stringify(Data)},function(ActionID){
					
				});
			}
		},
		SetPosition:function(top,left){
			this.ActionRequested();
			var coords = this.CheckForCollision(top,left);
			var NewTop = coords.top;
			var NewLeft = coords.left;
			this.UnitDiv.css({top:NewTop + 'px',left:NewLeft + 'px'});
		},
		Attack:function(Mode,event,EnemyUnit){
			if(Mode == 'Execute' && EnemyUnit.length > 0){
				this.ActionRequested();
				if(!event.ctrlKey){
					this.UnitDiv.clearQueue();
				}
				this.UnitDiv.queue(function(next){
					var UnitDiv = $(this);
					var DistanceToEnemy = UnitDiv.data().Unit.Distance(EnemyUnit.position().top,EnemyUnit.position().left,UnitDiv.position().top,UnitDiv.position().left);
					//alert(DistanceToEnemy);
					//alert(UnitDiv.data().Unit.Specs.weapon.Specs.range);
					if(DistanceToEnemy <= UnitDiv.data().Unit.weapon.range){
						UnitDiv.css({'background-color':'#000'});
						EnemyUnit.data().Unit.life-= Math.max(UnitDiv.data().Unit.weapon.attack - EnemyUnit.data().Unit.defense,1);
						EnemyUnit.html(EnemyUnit.data().Unit.life);
						if(EnemyUnit.data().Unit.life > 0){
							UnitDiv.data().Unit.attackTimeouts.push(setTimeout(function(){
								UnitDiv.data().Unit.Attack('Queue',{'ctrlKey':true},EnemyUnit);
							},UnitDiv.data().Unit.weapon.firerate));
						}else{
							UnitDiv.css({'background-color':'#CCC'});
							setTimeout(function(){
								EnemyUnit.remove();
							},1000);
						}
					}else{
						var MoveDistance = DistanceToEnemy - UnitDiv.data().Unit.weapon.range + 1;
						var NewTop = UnitDiv.position().top + ((EnemyUnit.position().top-UnitDiv.position().top) * MoveDistance/DistanceToEnemy);
						var NewLeft = UnitDiv.position().left + ((EnemyUnit.position().left-UnitDiv.position().left) * MoveDistance/DistanceToEnemy);
						UnitDiv.data().Unit.Move('Queue',{'ctrlKey':true},Math.round(NewTop),Math.round(NewLeft),UnitDiv.data().Unit.Attack,['Queue',{'ctrlKey':true},EnemyUnit]);
						//UnitDiv.data().Unit.Attack({'ctrlKey':true},EnemyUnit);
					}
					//UnitDiv.dequeue();
					next();
				});
			}else if(Mode == 'Queue'){
				//alert(this.UnitID);
				var Data = {"UnitID":this.UnitID,"EnemyUnitID":EnemyUnit.data().Unit.UnitID,"event":{'ctrlKey':event.ctrlKey}}
				$.post('ajax/index.php',{"Action":"AddAction","GameID":Settings.GameID,"Type":"AttackUnit","Data":JSON.stringify(Data)},function(ActionID){
					
				});
			}
		},
		Distance:function(top1,left1,top2,left2){
			var Dist = Math.sqrt(Math.pow(Math.abs(left1 - left2),2) + Math.pow(Math.abs(top1 - top2),2));
			return Dist;
		},
		ActionRequested:function(){
			this.UnitDiv.css({'background-color':'#CCC'});
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
	var that = this;
}