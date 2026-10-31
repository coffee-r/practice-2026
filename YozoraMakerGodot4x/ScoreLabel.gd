extends Label

func _ready():	
	# 位置初期化
	position = Vector2(0,-20)
	
	# 位置のアニメーション
	var tween = get_tree().create_tween()
	tween.tween_property(self, "position", Vector2(0, 0), 0.2).set_ease(Tween.EASE_OUT).set_trans(Tween.TRANS_BACK).set_delay(1)

func _on_player_add_score(star_count, grazing_count):
	# 星にかすっている時は星の数 * かすり数だけスコアを加算し、Grazeと表示
	# 星にかすっていない時は単純に+1スコアを追加し、スコアを表示
	if grazing_count >= 1:
		Score.value += star_count * grazing_count
		text = 'Graze!'
		$ViewGrazingTimer.start()
	else:
		Score.value += 1
		text = var_to_str(Score.value)

func _on_view_grazing_timer_timeout():
	# スコアを表示
	text = var_to_str(Score.value)
