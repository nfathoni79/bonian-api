<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 04/05/2019
 * Time: 1:27
 */

return [
  'Oauth' => [
      'redirectUri' => 'https://zolaku.dev.ridwan.id/oauth/cb/${provider}',
      'provider' => [
          'facebook' => [
              'applicationId' => '392709821576081',
              'applicationSecret' => '64721a2c89c0fa70ad5a1baeda1eb914',
              'scope' => ['email'],
              'options' => [
                  'identity.fields' => [
                      'email',
                      'picture.width(99999)'
                  ],
              ]
          ],
          'twitter' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'enabled' => false
          ],
          'google' => [
              'applicationId' => '254083460174-s43rpck2ho8v2iqs1l6lar9ov5hrtcl6.apps.googleusercontent.com',
              'applicationSecret' => 'og9WmBBnRgpKRmZokudpufCt',
              'scope' => [
                  'https://www.googleapis.com/auth/userinfo.email',
                  'https://www.googleapis.com/auth/userinfo.profile'
              ],
              'options' => [
                  'auth.parameters' => [
                      //'hd' => 'ridwan.id',
                  ]
              ]
          ],
          'paypal' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [
                  'profile',
                  'email',
                  'address',
                  'phone',
                  'https://uri.paypal.com/services/paypalattributes'
              ],
              'enabled' => false
          ],
          'vk' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => ['email'],
              'options' => [
                  'identity.fields' => [
                      'sex',
                      'screen_name',
                      'photo_max_orig',
                  ]
              ],
              'enabled' => false
          ],
          'github' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => ['user', 'email'],
              'options' => [
                  /**
                   * GitHub store only unverified and public email inside User
                   * It's not possible to fetch user with email in one query with GraphQL (new API)
                   * For now, there is only one way, additional request for it by user/email API entrypoint
                   *
                   * It's disabled by default in SocialConnect 1.x, but you can enable it from configuration :)
                   */
                  'fetch_emails' => true
              ],
              'enabled' => false
          ],
          'instagram' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'enabled' => false
          ],
          'slack' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [
                  'identity.basic',
                  'identity.email',
                  'identity.team',
                  'identity.avatar',
              ],
              'enabled' => false
          ],
          'twitch' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => ['user_read'],
              'enabled' => false
          ],
          'px500' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'enabled' => false
          ],
          'bitbucket' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => ['account'],
              'enabled' => false
          ],
          'amazon' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => ['profile'],
              'enabled' => false
          ],
          'gitlab' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => ['read_user'],
              'enabled' => false
          ],
          'vimeo' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [],
              'enabled' => false
          ],
          'digital-ocean' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [],
              'enabled' => false
          ],
          'yandex' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [],
              'enabled' => false
          ],
          //http://api.mail.ru/sites/my/add
          'mail-ru' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [],
              'enabled' => false
          ],
          //http://api.mail.ru/sites/my/add
          'odnoklassniki' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'applicationPublic' => '',
              'scope' => [
                  'GET_EMAIL'
              ],
              'enabled' => false
          ],
          'steam' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [],
              'enabled' => false
          ],
          'tumblr' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [],
              'enabled' => false
          ],
          'pixelpin' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [
                  'email'
              ],
              'enabled' => false
          ],
          // https://discordapp.com/developers/applications/me
          'discord' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [
                  'identify',
                  'email'
              ],
              'enabled' => false
          ],
          // https://apps.dev.microsoft.com/portal/register-app
          'microsoft' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [
                  'wl.basic',
                  'wl.birthday',
                  'wl.emails'
              ],
              'enabled' => false
          ],
          'smashcast' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [],
              'enabled' => false
          ],
          'steein' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [
                  'users',
                  'email'
              ],
              'enabled' => false
          ],
          // https://www.reddit.com/prefs/apps/
          'reddit' => [
              'applicationId' => '',
              'applicationSecret' => '',
              'scope' => [
                  'identity'
              ],
              'enabled' => false
          ],
          // https://www.linkedin.com/developer/apps
          'linkedin'  => [
              'applicationId'     => '',
              'applicationSecret' => '',
              'enabled' => false
          ],
          // https://developer.yahoo.com/apps/create/
          'yahoo'  => [
              'applicationId'     => '',
              'applicationSecret' => '',
              'enabled' => false
          ],
          // https://developer.wordpress.com/apps/
          'wordpress'  => [
              'applicationId'     => '',
              'applicationSecret' => '',
              'enabled' => false
          ],
      ]
  ]
];