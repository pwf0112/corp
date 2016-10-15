<?php
namespace Corp;

interface ApiUri
{
	const ACCESS_TOKEN = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=%s&corpsecret=%s';
	const USER_INFO = 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=%s&code=%s';
	const OAUTH = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=SCOPE&state=%s#wechat_redirect';
	const DEP_CREATE = 'https://qyapi.weixin.qq.com/cgi-bin/department/create?access_token=%s';
	const DEP_UPDATE = 'https://qyapi.weixin.qq.com/cgi-bin/department/update?access_token=%s';
	const DEP_DELETE = 'https://qyapi.weixin.qq.com/cgi-bin/department/delete?access_token=%s&id=%d';
	const DEP_LIST = 'https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token=%s&id=%d';
}