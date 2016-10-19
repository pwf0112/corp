<?php
namespace Corp;

interface Uri
{
	const ACCESS_TOKEN = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=%s&corpsecret=%s';
	const USER_INFO = 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=%s&code=%s';
	const OAUTH = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=SCOPE&state=%s#wechat_redirect';
	const DEP_CREATE = 'https://qyapi.weixin.qq.com/cgi-bin/department/create?access_token=%s';
	const DEP_UPDATE = 'https://qyapi.weixin.qq.com/cgi-bin/department/update?access_token=%s';
	const DEP_DELETE = 'https://qyapi.weixin.qq.com/cgi-bin/department/delete?access_token=%s&id=%d';
	const DEP_LIST = 'https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token=%s&id=%d';
	const USER_CREATE = 'https://qyapi.weixin.qq.com/cgi-bin/user/create?access_token=%s';
	const USER_UPDATE = 'https://qyapi.weixin.qq.com/cgi-bin/user/update?access_token=%s';
	const USER_DELETE = 'https://qyapi.weixin.qq.com/cgi-bin/user/delete?access_token=%s&userid=%s';
	const USER_BATCH_DELETE = 'https://qyapi.weixin.qq.com/cgi-bin/user/batchdelete?access_token=%s';
	const USER_GET = 'https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=%s&userid=%s';
	const USER_SIMPLE_LIST = 'https://qyapi.weixin.qq.com/cgi-bin/user/simplelist?access_token=%s&department_id=%d&fetch_child=%d&status=%d';
	const USER_LIST = 'https://qyapi.weixin.qq.com/cgi-bin/user/list?access_token=%s&department_id=%d&fetch_child=%d&status=%d';
}