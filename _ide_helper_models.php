<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Attachment
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUpdatedAt($value)
 */
	class Attachment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LineBot
 *
 * @property int $id
 * @property int $channel_id
 * @property string $channel_name
 * @property string $channel_secret
 * @property string $channel_access_token
 * @property string $basic_id
 * @property string $qrcode_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LineMessage> $messages
 * @property-read int|null $messages_count
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot query()
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot whereBasicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot whereChannelAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot whereChannelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot whereChannelSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot whereQrcodeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineBot whereUpdatedAt($value)
 */
	class LineBot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LineMessage
 *
 * @property int $id
 * @property int $line_bot_id
 * @property mixed $payload
 * @property bool $processed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LineBot|null $bot
 * @method static \Illuminate\Database\Eloquent\Builder|LineMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LineMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LineMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|LineMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineMessage whereLineBotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineMessage wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineMessage whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineMessage whereUpdatedAt($value)
 */
	class LineMessage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $line_user_id
 * @property string $name
 * @property string|null $aka
 * @property string|null $status
 * @property string|null $avatar_url
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAka($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLineUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserMessage
 *
 * @property int $id
 * @property int $user_id
 * @property int $line_bot_id
 * @property int $line_message_id
 * @property string|null $message
 * @property string $type
 * @property int|null $attachment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage whereAttachmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage whereLineBotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage whereLineMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMessage whereUserId($value)
 */
	class UserMessage extends \Eloquent {}
}

