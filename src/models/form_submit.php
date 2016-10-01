<?php

class FormSubmit extends Model {
  public function responses() {
    return $this->has_many('QuestionResponse');
  }
}
