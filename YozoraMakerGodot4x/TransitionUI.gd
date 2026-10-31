extends CanvasLayer

func _ready():
	# 位置の初期化
	$ColorRect.position = Vector2(-136, 0)

func transition_in():
	# 位置の初期化
	$ColorRect.position = Vector2(-136, 0)
	
	# 位置アニメーション
	var tween = get_tree().create_tween()
	tween.tween_property($ColorRect, "position", Vector2(0, 0), 0.4).set_ease(Tween.EASE_OUT).set_trans(Tween.TRANS_CIRC)
	await tween.finished

func transition_out():
	# 位置アニメーション
	var tween = get_tree().create_tween()
	tween.tween_property($ColorRect, "position", Vector2(136, 0), 0.4).set_ease(Tween.EASE_OUT).set_trans(Tween.TRANS_CIRC)
	await tween.finished
