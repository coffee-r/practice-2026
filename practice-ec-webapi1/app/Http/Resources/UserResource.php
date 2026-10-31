<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->userId,
            'name' => $this->userName,
            'nameFurigana' => $this->userNameFurigana,
            'gender' => $this->userGender,
            'birthdayYear' => $this->userBirthdayYear,
            'birthdayMonth' => $this->userBirthdayMonth,
            'email' => $this->userEmail,
            'password' => $this->userPassword,
            'addressPrefectures' => $this->userAddressPrefectures,
            'addressMunicipalities' => $this->userAddressMunicipalities,
            'addressOthers' => $this->userAddressOthers,
            'tel' => $this->userTel,
            'emailMagazineSubscription' => $this->userEmailMagazineSubscription,
        ];
    }
}
