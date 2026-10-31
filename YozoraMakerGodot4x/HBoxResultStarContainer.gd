extends HBoxContainer

# 星
@export var result_star: PackedScene

func animate():
	await get_tree().create_timer(0.5).timeout
	
	# 表示する星の数を決める
	var star_count = 0
	if Score.value < 100:
		star_count = 1
	elif Score.value < 300:
		star_count = 2
	elif Score.value < 500:
		star_count = 3
	elif Score.value < 1000:
		star_count = 4
	elif Score.value < 1500:
		star_count = 5
	elif Score.value < 2000:
		star_count = 6
	else:
		star_count = 7
	
	for i in star_count:
		await get_tree().create_timer(0.25).timeout
		var star = result_star.instantiate()
		add_child(star)
		
		# 星の数によって効果音を変える
		if i <= 3:
			$ShootSE.play()
		else:
			$GrazeSE.play()
		
	await get_tree().create_timer(0.25).timeout
