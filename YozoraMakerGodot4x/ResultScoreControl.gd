extends Control

func _ready():
	# ペイントアニメーションのアンカーの初期設定
	$PaintBackColorRect.offset_right = -50
	$PaintFrontColorRect.offset_right = -50

func animate():
	# 効果音
	$ResultSE.play()
	
	# ペイントアニメーション
	var tween = get_tree().create_tween()
	tween.set_parallel(true)
	tween.tween_property($PaintBackColorRect, "offset_right", 50, 0.25).set_trans(Tween.TRANS_CIRC)
	tween.tween_property($PaintFrontColorRect, "offset_right", 50, 0.25).set_trans(Tween.TRANS_CIRC).set_delay(0.1)
	await tween.finished
	
	# スコアテキスト表示
	$ResultScoreLabel.show()
	
	# ペイントアニメーション
	tween = get_tree().create_tween()
	tween.set_parallel(true)
	tween.tween_property($PaintFrontColorRect, "offset_left", 50, 0.25).set_trans(Tween.TRANS_CIRC)
	tween.tween_property($PaintBackColorRect, "offset_left", 50, 0.25).set_trans(Tween.TRANS_CIRC).set_delay(0.1)
	await tween.finished
	
	# 位置アニメーション
	tween = get_tree().create_tween()
	tween.tween_property(self, "position", Vector2(0, 50), 0.5).set_trans(Tween.TRANS_CIRC)
	await tween.finished
