extends Label

func _ready():
	position = Vector2(0, 410)
	
	# 点滅
	var tween = get_tree().create_tween()
	tween = get_tree().create_tween()
	tween.tween_property(self, "modulate", Color(1,1,1,1), 1).set_trans(Tween.TRANS_CUBIC)
	tween.tween_property(self, "modulate", Color(1,1,1,0), 1).set_trans(Tween.TRANS_CUBIC)
	tween.set_loops()

func animate():
	var tween = get_tree().create_tween()
	tween.tween_property(self, "position", Vector2(0,180), 1.0).set_trans(Tween.TRANS_CIRC)
