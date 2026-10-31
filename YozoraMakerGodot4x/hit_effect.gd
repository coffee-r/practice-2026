extends Node2D

func _ready():
	# スケールのアニメーション
	var tween = get_tree().create_tween()
	tween.set_parallel(true)
	tween.tween_property($Sprite2DHorizon, "scale", Vector2(500, 0.0), 0.1).set_delay(0.75)
	tween.tween_property($Sprite2DVertical, "scale", Vector2(0.0, 500), 0.1).set_delay(0.75)
