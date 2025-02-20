<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	ob_start();
?>
	<script type="text/javascript">
		var NextDivID = 1;
		var GameHeight = 600;
		var GameWidth = 600;
		var Paused = false;
		
		var Species = new Object();
		var Plant = new Object();
		Plant['Speed'] = 0;
		Plant['BurstSpeed'] = 0;
		Plant['Life'] = 5;
		Plant['Width'] = 1;
		Plant['Height'] = 1;
		Plant['AdultWidth'] = 30;
		Plant['AdultHeight'] = 30;
		Plant['ImagePath'] = '/life/plant.gif';
		Plant['FoodType'] = new Array();
		Plant['FoodType'][0] = 'None';
		Plant['ReproduceLife'] = 30;
		Plant['DeadLife'] = 10;
		Plant['PredatorType'] = new Array();
		Plant['PredatorType'][0] = 'Bug';
		Plant['SiteDistance'] = 100;
		Plant['LifePerTurn'] = 0.0001;
		Plant['LifeSpan'] = 6000;
		Plant['GrowthRate'] = 0.01;
		Plant['ReproductionLifeCost'] = 15;
		Plant['RotationAdjustment'] = 0;
		var Bug = new Object();
		Bug['Speed'] = 1;
		Bug['BurstSpeed'] = 2;
		Bug['Life'] = 20;
		Bug['Width'] = 1;
		Bug['Height'] = 1;
		Bug['AdultWidth'] = 10;
		Bug['AdultHeight'] = 15;
		Bug['ImagePath'] = '/life/bug.jpg';
		Bug['FoodType'] = new Array();
		Bug['FoodType'][0] = 'Plant';
		Bug['ReproduceLife'] = 50;
		Bug['DeadLife'] = 10;
		Bug['PredatorType'] = new Array();
		Bug['PredatorType'][0] = 'Fish';
		Bug['SiteDistance'] = 50;
		Bug['LifePerTurn'] = -0.0001;
		Bug['LifeSpan'] = 5000;
		Bug['GrowthRate'] = 0.01;
		Bug['ReproductionLifeCost'] = 0;
		Bug['RotationAdjustment'] = 90;
		var Fish = new Object();
		Fish['Speed'] = 1.1;
		Fish['BurstSpeed'] = 3;
		Fish['Life'] = 20;
		Fish['Width'] = 15;
		Fish['Height'] = 5;
		Fish['AdultWidth'] = 116;
		Fish['AdultHeight'] = 38;
		Fish['ImagePath'] = '/life/fish.gif';
		Fish['FoodType'] = new Array();
		Fish['FoodType'][0] = 'Bug';
		Fish['ReproduceLife'] = 200;
		Fish['DeadLife'] = 10;
		Fish['PredatorType'] = new Array();
		Fish['PredatorType'][0] = 'None';
		Fish['SiteDistance'] = 200;
		Fish['LifePerTurn'] = -0.0001;
		Fish['LifeSpan'] = 10000;
		Fish['GrowthRate'] = 0.01;
		Fish['ReproductionLifeCost'] = 15;
		Fish['RotationAdjustment'] = 0;
		
		/*var BigFish = new Array();
		BigFish['Speed'] = 1.2;
		BigFish['BurstSpeed'] = 3.2;
		BigFish['Life'] = 50;
		BigFish['Width'] = 25;
		BigFish['Height'] = 10;
		BigFish['AdultWidth'] = 232;
		BigFish['AdultHeight'] = 76;
		BigFish['ImagePath'] = '/life/fish.jpg';
		BigFish['FoodType'] = new Array();
		BigFish['FoodType'][0] = 'Bug';
		BigFish['FoodType'][1] = 'Fish';
		BigFish['ReproduceLife'] = 10000;
		BigFish['DeadLife'] = 100;
		BigFish['PredatorType'] = new Array();
		BigFish['PredatorType'][0] = 'None';
		BigFish['SiteDistance'] = 300;
		BigFish['LifePerTurn'] = -0.00001;
		BigFish['LifeSpan'] = 100000;
		BigFish['GrowthRate'] = 0.06;
		BigFish['ReproductionLifeCost'] = 15;*/
		
		Species['Bug'] = Bug;
		Species['Fish'] = Fish;
		Species['Plant'] = Plant;
		//Species['BigFish'] = BigFish;
		
		var AnimalInfo = new Object();
		
		Object.find = function(ary, element){
			for(var i=0; i<ary.length; i++){
				if(ary[i] == element){
					return true;
				}
			}
			return false;
		}
		
		Object.filter = function(fun , thisp)
		  {
			var len = this.length;
			if (typeof fun != "function")
			  throw new TypeError();
		
			var res = new Object();
			var thisp = arguments[1];
			for (var i = 0; i < len; i++)
			{
			  if (i in this)
			  {
				var val = this[i]; // in case fun mutates this
				if (fun.call(thisp, val, i, this))
				  res.push(val);
			  }
			}
		
			return res;
		  };
		
		function TogglePause(){
			if(Paused){
				Paused = false;
				document.getElementById('PauseButton').innerHTML = 'Pause';
			}else{
				Paused = true;
				document.getElementById('PauseButton').innerHTML = 'Play';
			}
		}
		
		function Start(){
			GameArea = document.getElementById('GameDiv');
			TimeCell = document.getElementById('TimeCell');
			editingAnimal = 0;
			var ControlArea = document.getElementById('ControlCell');
			ControlArea.innerHTML = '';
			for(Type in Species){
				ControlArea.innerHTML+='<div><button onclick="AddAnimal(\'' + Type + '\',0,0,true,false)">Add ' + Type + '</button> <button onclick="EditType(\'' + Type + '\');">Edit ' + Type + '</button> <span id="' + Type + 'Count">0</span></div>';
			}
			getSaves();
		}
		
		function EditType(Type){
			
			EditForm = document.getElementById('SpeciesEditForm');
			if(EditForm){
				EditForm.parentNode.removeChild(EditForm);
			}
			
			var SpeciesEdit = document.createElement('div');
			GameArea.appendChild(SpeciesEdit);
			SpeciesEdit.id = 'SpeciesEditForm';
			SpeciesEdit.style.position = 'absolute';
			SpeciesEdit.style.top = '4px';
			SpeciesEdit.style.left = '4px';
			SpeciesEdit.style.zIndex = 1000;
			SpeciesEdit.style.backgroundColor = 'white';
			SpeciesEdit.style.padding = '4px';
			SpeciesEdit.style.border = '1px solid black';
			for(property in Species[Type]){
				if(typeof Species[Type][property] == 'number' | typeof Species[Type][property] == 'string' | typeof Species[Type][property] == 'object'){
					var nextLabel = document.createElement('span');
					nextLabel.innerHTML = property + ':';
					SpeciesEdit.appendChild(nextLabel);
					var nextInput = document.createElement('input');
					nextInput.type = 'text';
					nextInput.name = property;
					nextInput.id = property;
					if(typeof Species[Type][property] == 'number' | typeof Species[Type][property] == 'string'){
						nextInput.value = Species[Type][property];
					}
					if(typeof Species[Type][property] == 'object'){
						for(thing in Species[Type][property]){
							nextInput.value += Species[Type][property][thing];
							if(thing + 1 < Species[Type][property].length){
								nextInput.value += ',';
							}
						}
					}
					SpeciesEdit.appendChild(nextInput);
					var nextBreak = document.createElement('br');
					SpeciesEdit.appendChild(nextBreak);
				}
			}
			
			editingType = Type;
			
			var nextInput = document.createElement('input');
			nextInput.type = 'button';
			nextInput.name = 'Finish';
			nextInput.value = 'Save ' + Type;
			nextInput.onclick = FinishEditingType;
			SpeciesEdit.appendChild(nextInput);
			//SpeciesEdit.innerHTML = '<div id="SpeciesEditForm" onclick="FinishEditingType();">hello</div>';
			//alert(Type);
		}
		
		function FinishEditingType(){
			//alert(editingType);
			for(property in Species[editingType]){
				if(typeof Species[editingType][property] == 'number' | typeof Species[Type][property] == 'string' | typeof Species[Type][property] == 'object'){
					var inputField = document.getElementById(property);
					
					if(typeof Species[Type][property] == 'string'){
						Species[editingType][property] = inputField.value;
					}
					if(typeof Species[Type][property] == 'number'){
						Species[editingType][property] = inputField.value * 1;
					}
					if(typeof Species[Type][property] == 'object'){
						Species[editingType][property] = inputField.value.split(',');
					}
				}
			}
			/*for(property in Species[editingType]){
				alert(Species[Type][property]);
			}*/
			EditForm = document.getElementById('SpeciesEditForm');
			EditForm.parentNode.removeChild(EditForm);
		}
		
		function AddAnimal(Type,Top,Left,RandomPosition,HasParent,ParentID){
			document.getElementById(Type + 'Count').innerHTML++;
			var NewAnimal = document.createElement('div');
			var NewAnimalLife = document.createElement('div');
			var ThisSpecies = Species[Type];
			
			if(HasParent)
			{
				var Parent = AnimalInfo[ParentID];
				var Mutation = (Math.random() * 0.02) - 0.01;
			}
			else
			{
				var Parent = Species[Type];
				var Mutation = 0;
			}
			
			NewAnimalID = NextDivID;
			NextDivID++;
			
			NewAnimal.id = Type + NewAnimalID;
			NewAnimal.style.height = ThisSpecies['Height'] + 'px';
			NewAnimal.style.width = ThisSpecies['Width'] + 'px';
			NewAnimal.style.position = 'absolute';
			NewAnimal.style.top = Top + 'px';
			NewAnimal.style.left = Left + 'px';
			if(RandomPosition)
			{
				var RandomTop = Math.floor(Math.random() * GameHeight);
				var RandomLeft = Math.floor(Math.random() * GameWidth);
				
				NewAnimal.style.top = RandomTop + 'px';
				NewAnimal.style.left = RandomLeft + 'px';
			}
			NewAnimal.innerHTML = '<img src="' + ThisSpecies['ImagePath'] + '" id="' + Type + NewAnimalID + 'Image" border="0" alt="' + ThisSpecies['ImagePath'] + '" style="width:' + ThisSpecies['Width'] + 'px; height:' + ThisSpecies['Height'] + 'px;">';
			NewAnimal.onclick = showAnimalInfo;
			GameArea.appendChild(NewAnimal);
			NewAnimalInfo = new Object();
			NewAnimalInfo['ID'] = Type + NewAnimalID;
			NewAnimalInfo['Type'] = Type;
			NewAnimalInfo['Life'] = ThisSpecies['Life'];
			NewAnimalInfo['Speed'] = Parent['Speed'] * (1 + Mutation);
			NewAnimalInfo['BurstSpeed'] = Parent['BurstSpeed'] * (1 + Mutation);
			NewAnimalInfo['Top'] = Top;
			NewAnimalInfo['Left'] = Left;
			if(RandomPosition)
			{
				NewAnimalInfo['Top'] = RandomTop;
				NewAnimalInfo['Left'] = RandomLeft;
			}
			NewAnimalInfo['Width'] = ThisSpecies['Width'];
			NewAnimalInfo['Height'] = ThisSpecies['Height'];
			NewAnimalInfo['LifeSpan'] = ThisSpecies['LifeSpan'];
			NewAnimalInfo['GrowthRate'] = ThisSpecies['GrowthRate'];
			NewAnimalInfo['Dead'] = false;
			NewAnimalInfo['LeftMultiplier'] = Math.floor(Math.random() * 3) - 1;
			NewAnimalInfo['TopMultiplier'] = Math.floor(Math.random() * 3) - 1;
			AnimalInfo[Type + NewAnimalID] = NewAnimalInfo;
			
			/* Stats */
			//DisplayInformation();
			
			var AnimalImage = document.getElementById(Type + NewAnimalID + 'Image');
			
			MoveAnimal(NewAnimal,AnimalImage);
		}
		
		function calculateRotation(newLeft,oldLeft,newTop,oldTop,Adjustment){
			
			var NewRotation = Math.atan(Math.abs(newTop - oldTop) / Math.abs(newLeft - oldLeft));
			NewRotation = NewRotation * (180 / Math.PI);
			
			if(newTop > oldTop){
				if(newLeft > oldLeft){
					//NewRotation += 180;
					//$(fish).html('down right ' + NewRotation);
				}else{
					NewRotation = 180 - NewRotation;
					//$(fish).html('down left ' + NewRotation);
				}
			}else{
				if(newLeft > oldLeft){
					NewRotation = 360 - NewRotation;
					//$(fish).html('up right ' + NewRotation);
				}else{
					NewRotation = NewRotation + 180;
					//$(fish).html('up left ' + NewRotation);					
				}
			}
			
			return NewRotation + Adjustment;
		}
		
		function MoveAnimal(ThisAnimal,ThisAnimalImage){
			//alert(ThisAnimal);
			//alert(ThisAnimalImage);
			if(!Paused){
				TimeCell.innerHTML++;
				if(AnimalInfo[ThisAnimal.id])
				{
					var ThisAnimalInfo = AnimalInfo[ThisAnimal.id];
					var ThisSpeciesInfo = Species[ThisAnimalInfo['Type']];
					var ThisAnimalArea = ThisAnimalInfo['Height'] * ThisAnimalInfo['Width'];
					
					var newTime = new Date().getTime();
					
					if(newTime % 20 == 0){
						ThisAnimalInfo['LeftMultiplier'] = Math.floor(Math.random() * 3) - 1;
						ThisAnimalInfo['TopMultiplier'] = Math.floor(Math.random() * 3) - 1;
					}
					
					var NewLeft = ThisAnimalInfo['Left'] + (ThisAnimalInfo['Speed'] * ThisAnimalInfo['LeftMultiplier']);
					var NewTop = ThisAnimalInfo['Top'] + (ThisAnimalInfo['Speed'] * ThisAnimalInfo['TopMultiplier']);
					
					
					/* Stats */
					//DisplayInformation();
					
					/* Adjust Animal Numbers */
					ThisAnimalInfo['Life']+=ThisSpeciesInfo['LifePerTurn'] * ThisAnimalArea;
					ThisAnimalInfo['LifeSpan']--;
					
					if(ThisAnimalInfo['Height'] < ThisSpeciesInfo['AdultHeight'])
					{
						ThisAnimalInfo['Height']+=ThisAnimalInfo['GrowthRate'];
						ThisAnimal.style.height = ThisAnimalInfo['Height'] + 'px';
						ThisAnimalImage.style.height = ThisAnimalInfo['Height'] + 'px';
					}
					
					if(ThisAnimalInfo['Width'] < ThisSpeciesInfo['AdultWidth'])
					{
						ThisAnimalInfo['Width']+=ThisAnimalInfo['GrowthRate'];
						ThisAnimal.style.width = ThisAnimalInfo['Width'] + 'px';
						ThisAnimalImage.style.width = ThisAnimalInfo['Width'] + 'px';
						ThisAnimal.style.zIndex =  Math.round(ThisAnimalInfo['Width']);
					}
					
					/* Check Life */
					if (ThisAnimalInfo['Life'] <= 0 | ThisAnimalInfo['LifeSpan'] <= 0){
						ThisAnimalInfo['Dead'] = true;
					}
					
					
					if(!ThisAnimalInfo['Dead'])
					{
						/* Reproduce */
						if(ThisAnimalInfo['Life'] >= ThisSpeciesInfo['ReproduceLife'])
						{
							ThisAnimalInfo['Life']-= ThisSpeciesInfo['Life'];
							ThisAnimalInfo['Life']-= ThisSpeciesInfo['ReproductionLifeCost'];
							var ReproduceTop = ThisAnimalInfo['Top'] + Math.floor((Math.random() * (ThisAnimalInfo['Height'] * 6)) - (ThisAnimalInfo['Height'] * 3));
							var ReproduceLeft = ThisAnimalInfo['Left'] + Math.floor((Math.random() * (ThisAnimalInfo['Width'] * 6)) - (ThisAnimalInfo['Width'] * 3));
							var ReproduceType = ThisAnimalInfo['Type'];
							if(ReproduceLeft > GameWidth)
							{
								ReproduceLeft = ReproduceLeft - GameWidth;
							}
							if(ReproduceLeft < 0)
							{
								ReproduceLeft = GameWidth + ReproduceLeft;
							}
							if(ReproduceTop > GameHeight)
							{
								ReproduceTop = ReproduceTop - GameHeight;
							}
							if(ReproduceTop < 0)
							{
								ReproduceTop = GameHeight + ReproduceTop;
							}
							AddAnimal(ReproduceType,ReproduceTop,ReproduceLeft,false,true,ThisAnimal.id);
						}
						
						if(ThisAnimalInfo['Speed'] > 0 || ThisAnimalInfo['BurstSpeed'] > 0){
							/* Find Closest Food */
							var ClosestFoodDistance = 2000;
							var ClosestFoodID = '';
							for(var AnimalIDs in AnimalInfo){
								var ThisFood = AnimalInfo[AnimalIDs];
								var ThisFoodArea = ThisFood['Height'] * ThisFood['Width'];
								if(Object.find(ThisSpeciesInfo['FoodType'],ThisFood['Type']) && ThisAnimalArea > ThisFoodArea && AnimalIDs != ThisAnimal.id){
									var FoodDistance = Math.sqrt(Math.pow(ThisAnimalInfo['Left']-ThisFood['Left'],2) + Math.pow(ThisAnimalInfo['Top']-ThisFood['Top'],2));
									if (FoodDistance < ClosestFoodDistance){
										ClosestFoodDistance = FoodDistance;
										ClosestFoodID = ThisFood['ID'];
									}
								}
							}
							
							/* Find Closest Predator */
							var ClosestPredatorDistance = 2000;
							var ClosestPredatorID = '';
							for(var AnimalIDs in AnimalInfo){
								var ThisPredator = AnimalInfo[AnimalIDs];
								var ThisPredatorArea = ThisPredator['Height'] * ThisPredator['Width'];
								if(Object.find(ThisSpeciesInfo['PredatorType'],ThisPredator['Type']) && ThisPredatorArea > ThisAnimalArea && !ThisPredator['Dead'] && AnimalIDs != ThisAnimal.id){
									var PredatorDistance = Math.sqrt(Math.pow(ThisAnimalInfo['Left']-ThisPredator['Left'],2) + Math.pow(ThisAnimalInfo['Top']-ThisPredator['Top'],2));
									if (PredatorDistance < ClosestPredatorDistance){
										ClosestPredatorDistance = PredatorDistance;
										ClosestPredatorID = ThisPredator['ID'];
									}
								}
							}
							
							/* Pick Speed */
							if(ClosestPredatorDistance < ThisSpeciesInfo['SiteDistance'] | ClosestFoodDistance < ThisSpeciesInfo['SiteDistance'])
							{
								var MoveSpeed = ThisAnimalInfo['BurstSpeed'];
							}
							else
							{
								var MoveSpeed = ThisAnimalInfo['Speed'];
							}
							
							/* If Food Found */
							if(AnimalInfo[ClosestFoodID] && ClosestFoodDistance < ThisSpeciesInfo['SiteDistance'])
							{
								var ClosestFoodInfo = AnimalInfo[ClosestFoodID];
							
							
								/* Eat Food - Grow animal, add to food eaten and mark eaten animal as dead */
								if(ClosestFoodDistance < 5){
									ClosestFoodSpeciesInfo = Species[ClosestFoodInfo['Type']];
									ThisAnimalInfo['Life']+=ClosestFoodInfo['Life'];
									ClosestFoodInfo['Dead'] = true;
									//ClosestFoodInfo['Life'] = ClosestFoodSpeciesInfo['DeadLife'];
									RemoveAnimal(ClosestFoodID);
								}
							
							
								/* Move Animal toward food*/
								ThisAnimalInfo['Life']-= MoveSpeed * 0.01;
								var NewLeft = ThisAnimalInfo['Left'] + ((ClosestFoodInfo['Left']-ThisAnimalInfo['Left']) * MoveSpeed/ClosestFoodDistance);
								
								var NewTop = ThisAnimalInfo['Top'] + ((ClosestFoodInfo['Top']-ThisAnimalInfo['Top']) * MoveSpeed/ClosestFoodDistance);
								
								
								
							}
							
							/* If Predator Found */
							if(AnimalInfo[ClosestPredatorID] && ClosestPredatorDistance < ThisSpeciesInfo['SiteDistance'])
							{
								var ClosestPredatorInfo = AnimalInfo[ClosestPredatorID];
								
								/* Move Animal Away from Predator */
								ThisAnimalInfo['Life']-= MoveSpeed * 0.005;
								var NewLeft = ThisAnimalInfo['Left'] - ((ClosestPredatorInfo['Left']-ThisAnimalInfo['Left']) * MoveSpeed/ClosestPredatorDistance);
								
							
								var NewTop = ThisAnimalInfo['Top'] - ((ClosestPredatorInfo['Top']-ThisAnimalInfo['Top']) * MoveSpeed/ClosestPredatorDistance);
								
								
							}
							
							if(NewLeft <= 0)
							{
								NewLeft = GameWidth + NewLeft;
							}
							
							if(NewLeft >= GameWidth)
							{
								NewLeft = NewLeft - GameWidth;
							}
							
							if(NewTop <= 0)
							{
								NewTop = GameHeight + NewTop;
							}
							
							if(NewTop >= GameHeight)
							{
								NewTop = NewTop - GameHeight;
							}
							
							var NewRotation = calculateRotation(NewLeft,AnimalInfo[ThisAnimal.id]['Left'],NewTop,AnimalInfo[ThisAnimal.id]['Top'],ThisSpeciesInfo['RotationAdjustment'])//Math.tan(Math.abs(NewTop - AnimalInfo[ThisAnimal.id]['Top']) / Math.abs(NewLeft - AnimalInfo[ThisAnimal.id]['Left']));
							
							ThisAnimalImage.style.MozTransform = 'rotate(' + NewRotation + 'deg)';
							ThisAnimalImage.style.WebkitTransform = 'rotate(' + NewRotation + 'deg)';
							
							ThisAnimal.style.left = NewLeft + 'px';
							AnimalInfo[ThisAnimal.id]['Left'] = NewLeft;
							ThisAnimal.style.top = NewTop + 'px';
							AnimalInfo[ThisAnimal.id]['Top'] = NewTop;
							
						}
						
						AnimalInfo.Timeout = setTimeout(function(){MoveAnimal(ThisAnimal,ThisAnimalImage);},100);
					}
					else
					{
						ThisAnimal['Life'] = ThisSpeciesInfo['DeadLife'];
						AnimalInfo.DeadTimeout = setTimeout('RemoveAnimal(\'' + ThisAnimal.id + '\')',20000);
					}
				}
			}else{
				AnimalInfo.PauseTimeout = setTimeout(function(){MoveAnimal(ThisAnimal,ThisAnimalImage);},1000);
			}
		}
		
		function RemoveAnimal(AnimalID){
			//TimeCell.innerHTML--;
			ThisAnimalInfo = AnimalInfo[AnimalID];
			var Type = ThisAnimalInfo['Type'];
			document.getElementById(Type + 'Count').innerHTML--;
			delete AnimalInfo[AnimalID];
			var ThisAnimal = document.getElementById(AnimalID);
			GameArea.removeChild(ThisAnimal);
		}
		
		function showAnimalInfo(){
			//if(Paused){
				var EditForm = document.getElementById('SpeciesEditForm');
				if(EditForm){
					EditForm.parentNode.removeChild(EditForm);
				}
				
				if(document.getElementById(editingAnimal)){
					document.getElementById(editingAnimal).style.border = 'none';
				}
				
				this.style.border = '1px solid red';
				
				var AnimalEdit = document.createElement('div');
				GameArea.appendChild(AnimalEdit);
				AnimalEdit.id = 'SpeciesEditForm';
				AnimalEdit.style.position = 'absolute';
				AnimalEdit.style.top = '4px';
				AnimalEdit.style.left = '4px';
				AnimalEdit.style.zIndex = 1000;
				AnimalEdit.style.backgroundColor = 'white';
				AnimalEdit.style.padding = '4px';
				AnimalEdit.style.border = '1px solid black';
				for(property in AnimalInfo[this.id]){
					if(typeof AnimalInfo[this.id][property] == 'number'){
						var nextLabel = document.createElement('span');
						nextLabel.innerHTML = property + ':';
						AnimalEdit.appendChild(nextLabel);
						var nextInput = document.createElement('input');
						nextInput.type = 'text';
						nextInput.name = property;
						nextInput.id = property;
						nextInput.value = AnimalInfo[this.id][property];
						AnimalEdit.appendChild(nextInput);
						var nextBreak = document.createElement('br');
						AnimalEdit.appendChild(nextBreak);
					}
				}
				
				editingAnimal = this.id;
				
				var nextInput = document.createElement('input');
				nextInput.type = 'button';
				nextInput.name = 'Finish';
				nextInput.value = 'Save Animal';
				nextInput.onclick = FinishEditingAnimal;
				AnimalEdit.appendChild(nextInput);
			//}
		}
		
		function FinishEditingAnimal(){
			//alert(editingType);
			for(property in AnimalInfo[editingAnimal]){
				if(typeof AnimalInfo[editingAnimal][property] == 'number'){
					var inputField = document.getElementById(property);
					AnimalInfo[editingAnimal][property] = inputField.value * 1;
				}
			}
			/*for(property in Species[editingType]){
				alert(Species[Type][property]);
			}*/
			document.getElementById(editingAnimal).style.border = 'none';
			EditForm = document.getElementById('SpeciesEditForm');
			EditForm.parentNode.removeChild(EditForm);
			editingAnimal = 0;
		}
		
		function displaySaves(data){
			var SavedGameContainer = document.getElementById('SavedGames');
			SavedGameContainer.innerHTML = '';
			for(save in data){
				var newSave = document.createElement('div');
				SavedGameContainer.appendChild(newSave);
				var newName = document.createElement('span');
				newName.innerHTML = data[save]['Name'] + ' ' + data[save]['LifeSaveID'];
				newName.id = 'SavedGame_' + data[save]['LifeSaveID'];
				newName.onclick = loadGame;
				newSave.appendChild(newName);
				var newdelete = document.createElement('span');
				newdelete.innerHTML = 'Delete';
				newdelete.id = 'DeleteGame_' + data[save]['LifeSaveID'];
				newdelete.onclick = deleteGame;
				newSave.appendChild(newdelete);
			}
		}
		
		function getSaves(){
			
			var formFields = new Object;
			formFields.Action = 'GetSaves';
			
			$.post('/common/lifeSave.php',formFields,function(data){
				displaySaves(data);
			});
		}
		
		function saveGame(){
			var EncodedData = new Object;
			EncodedData.Species = Species;
			EncodedData.AnimalInfo = AnimalInfo;
			
			var formFields = new Object;
			formFields.Action = 'Save';
			/*if(LifeSaveID){
				formFields.LifeSaveID = LifeSaveID;
			}*/
			formFields.Name = document.getElementById('SavedName').value;
			formFields.GameData = JSON.stringify(EncodedData);
			//$('#ControlCell').html(formFields.GameData);
			//alert(formFields.GameData);
			
			$.post('/common/lifeSave.php',formFields,function(data){
				displaySaves(data);
			});
		}
		
		function loadGame(){
			if(!Paused){
				TogglePause();
			}
			GameArea.innerHTML = '';
			TimeCell.innerHTML = 0;
			for(Animal in AnimalInfo){
				var TimeAnimal = AnimalInfo[Animal];
				clearTimeout(TimeAnimal.Timeout);
				clearTimeout(TimeAnimal.DeadTimeout);
				clearTimeout(TimeAnimal.PauseTimeout);
			}
			for(Type in Species){
				document.getElementById(Type + 'Count').innerHTML = 0;
			}
			//NextDivID = 1;
			var formFields = new Object;
			formFields.Action = 'Load';
			formFields.LifeSaveID = this.id.split('_')[1];
			
			//alert(this.id.split('_')[1]);
			
			$.post('/common/lifeSave.php',formFields,function(data){
				Species = data.Species;
				AnimalInfo = data.AnimalInfo;
				/*for(Animal in AnimalInfo){
					alert(AnimalInfo[Animal]);
				}
				return true;*/
				for(Animal in AnimalInfo){
					var ThisAnimal = AnimalInfo[Animal];
					//NewAnimalID = ThisAnimal.id;
					//NextDivID++;
					
					var ImagePath = Species[ThisAnimal.Type]['ImagePath'];
					
					var NewAnimal = document.createElement('div');
					
					NewAnimal.id = ThisAnimal.ID;
					NewAnimal.style.height = ThisAnimal['Height'] + 'px';
					NewAnimal.style.width = ThisAnimal['Width'] + 'px';
					NewAnimal.style.position = 'absolute';
					NewAnimal.style.top = ThisAnimal['Top'] + 'px';
					NewAnimal.style.left = ThisAnimal['Left'] + 'px';
					
					NewAnimal.innerHTML = '<img src="' + ImagePath + '" id="' + ThisAnimal.ID + 'Image" border="0" alt="' + ImagePath + '" style="width:' + ThisAnimal['Width'] + 'px; height:' + ThisAnimal['Height'] + 'px;">';
					NewAnimal.onclick = showAnimalInfo;
					GameArea.appendChild(NewAnimal);
					
					var AnimalImage = document.getElementById(ThisAnimal.ID + 'Image');
					
					document.getElementById(ThisAnimal.Type + 'Count').innerHTML++;
					
					MoveAnimal(NewAnimal,AnimalImage);
				}
				TogglePause();
			});
		}
		
		function deleteGame(){
			var formFields = new Object;
			formFields.Action = 'Delete';
			formFields.LifeSaveID = this.id.split('_')[1];
			
			$.post('/common/lifeSave.php',formFields,function(data){
				displaySaves(data);
			});
		}
		
		/*function DisplayInformation(){
			StatsArea = document.getElementById('StatsCell');
			StatsArea.innerHTML = '<br><strong>Animal Information</strong><br><br>';
			
			for(Type in Species){
				var SpeciesTotal = Species.length;//Array.filter(function() {return this['Type'] == Type},AnimalInfo).length;
				StatsArea.innerHTML+= Type + ':' + SpeciesTotal + '<br>';
			}
			
			for(Animal in AnimalInfo){
				var ThisAnimalInfo = AnimalInfo[Animal];
				StatsArea.innerHTML+= '<strong>' + ThisAnimalInfo['ID'] + '</strong> Life:' + Math.round(ThisAnimalInfo['Life']) + '<br>';
				StatsArea.innerHTML+= 'Speed:' + Math.round(ThisAnimalInfo['Speed']) + '<br>';
				StatsArea.innerHTML+= 'Burst Speed:' + Math.round(ThisAnimalInfo['BurstSpeed']) + '<br>';
			}
		}*/
		
		window.onload = Start;
	</script>
	
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody>
			<tr>
				<td colspan="2"><p>Click the add buttons for the different life forms to add one to the game area.  They start very small so it takes a minute or so before you can see the plants or bugs. The bugs eat the plants and the fish eat the bugs. The plants will spread and the bugs and fish will reproduce as they eat.</p></td>
			</tr>
			<tr>
				<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td id="ControlCell"></td>
							</tr>
							<tr>
								<td><button id="PauseButton" onclick="TogglePause();">Pause</button></td>
							</tr>
							<?php
								if($site->UserLoggedIn()){
							?>
								<tr>
									<td><button id="SaveButton" onclick="saveGame();">Save</button> <input type="text" id="SavedName" value="Game" /></td>
								</tr>
							<?php
								}
							?>
							<tr>
								<td id="TimeCell">0</td>
							</tr>		
						</tbody>
					</table>
				</td>
				<td>
					<input type="text" name="GameBG" id="GameBG" value="/life/game_bg.jpg" /> <button onclick="GameArea.style.backgroundImage = 'url(' + document.getElementById('GameBG').value + ')';">Set Background</button>
					<div id="SavedGames">
						<?php
							if(!$site->UserLoggedIn()){
								echo '<div><a href="/modules/profile/signup.php">Signup</a> or <a href="/modules/profile/index.php">Login</a> to save your game</div>';
							}
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" valign="top"><div id="GameDiv" style="position:relative; height:600px; width:600px; border:1px solid #000; overflow:hidden;"></div></td>
			</tr>
		</tbody>
	</table>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
