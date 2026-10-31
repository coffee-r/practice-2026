extends Label

func _ready():
	# 点滅アニメーション
	var tween = get_tree().create_tween()
	tween.tween_property(self, "modulate", Color(1,1,1,0), 1).set_trans(Tween.TRANS_CUBIC)
	tween.tween_property(self, "modulate", Color(1,1,1,1), 1).set_trans(Tween.TRANS_CUBIC)
	tween.set_loops()
