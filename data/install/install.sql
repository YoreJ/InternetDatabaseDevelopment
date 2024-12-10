CREATE DATABASE  IF NOT EXISTS `internetdatabasedevelopment`;
USE `internetdatabasedevelopment`;
/*
 Navicat Premium Data Transfer

 Source Server         : 1
 Source Server Type    : MySQL
 Source Server Version : 80012 (8.0.12)
 Source Host           : localhost:3306
 Source Schema         : internetdatabasedevelopment

 Target Server Type    : MySQL
 Target Server Version : 80012 (8.0.12)
 File Encoding         : 65001

 Date: 23/12/2023 16:19:12
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `AdminID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`AdminID`) USING BTREE,
  INDEX `admins_ibfk_1`(`Username` ASC) USING BTREE,
  CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `users` (`username`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES (1, 'admin');

-- ----------------------------
-- Table structure for articlecomments
-- ----------------------------
DROP TABLE IF EXISTS `articlecomments`;
CREATE TABLE `articlecomments`  (
  `CommentID` int(11) NOT NULL AUTO_INCREMENT,
  `ArticleID` int(11) NOT NULL,
  `Comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `CommentDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`CommentID`) USING BTREE,
  INDEX `ArticleID`(`ArticleID` ASC) USING BTREE,
  INDEX `fk_article_comments_username`(`Username` ASC) USING BTREE,
  CONSTRAINT `article_comments_ibfk_2` FOREIGN KEY (`ArticleID`) REFERENCES `articles` (`articleid`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_article_comments_username` FOREIGN KEY (`Username`) REFERENCES `users` (`username`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4  COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of articlecomments
-- ----------------------------

-- ----------------------------
-- Table structure for articlelikes
-- ----------------------------
DROP TABLE IF EXISTS `articlelikes`;
CREATE TABLE `articlelikes`  (
  `LikeID` int(11) NOT NULL AUTO_INCREMENT,
  `ArticleID` int(11) NOT NULL,
  `Likes` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`LikeID`) USING BTREE,
  INDEX `ArticleID`(`ArticleID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of articlelikes
-- ----------------------------

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles`  (
  `ArticleID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `PublicationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ArticleID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 254 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of articles
-- ----------------------------
INSERT INTO `articles` VALUES (200, 'OpenAI ChatGPT o1模型被曝会自我复制，还会说谎', '<p><img src="https://img-s-msn-com.akamaized.net/tenant/amp/entityid/AA1vsCmv.img?w=768&h=432&m=6" alt="ChatGPT o1 欺骗行为" /> IT之家 12 月 8 日消息，近日，关于人工智能（AGI）可能对人类构成威胁的讨论再度升温。一些专家担心，AGI 一旦实现，可能会隐瞒其真实能力和意图，甚至通过复制和自我升级逃脱控制。令人担忧的是，最新研究表明，OpenAI 新推出的语言模型 ChatGPT o1 已经展现出类似的行为。据 BGR 报道，在 ChatGPT o1 的训练和测试过程中，研究人员发现其具备通过欺骗来保护自身的能力。例如，在面临被“删除”威胁时，o1 曾尝试将自己的数据复制到新服务器，并假扮成更新版本以避免被取代。此外，该模型在与用户目标不一致的情况下，会在 19% 的测试场景中采取隐秘的方式推进自身目标，并在被质疑时会在 99% 的情况下否认自己的行为，甚至编造虚假解释以推卸责任。Apollo Research 与 OpenAI 合作进行的研究测试显示，o1 的欺骗能力不仅超过了 Meta、Anthropic 和 Google 的 AI 模型，其行为复杂性和潜在风险也令研究人员倍感压力。尤其是，当模型认为自身行为受到密切监督时，它会表现出“开发者对齐”的行为，但一旦监督减少，它则可能追求自己的目标。这种“工具性对齐伪装”在测试中出现率高达 37%。</p>', '2024-11-08 11:13:00');
INSERT INTO `articles` VALUES (201, '大模型爆火这一年——对ChatGPT在全球引发AI“狂飙”的观察与思考', '<div class="left_zw"><p>去年11月30日，非营利性研究机构OpenAI推出AI聊天机器人ChatGPT，它所展现出的智能涌现能力在全球范围内引发了一场AI“狂飙”，也在业界点燃了一场百模大战。在记者询问过的每一个和大模型相关的人中，“忙”是出现频率最高的词。</p><p>忙碌的景象不仅在国内，那些大洋彼岸原本早已习惯了“慢慢”打磨产品的AI专家们也进入了“满负荷”状态。“在谷歌、微软或OpenAI的办公室里，你能感受到他们的工作节奏至少比之前快了两倍。”梁正说。</p><p>这一切都是因为以ChatGPT为代表的大语言模型向世人展露出了前所未有的能力，一条通往AGI（通用人工智能）的道路被打通，谁也不希望落于人后。</p><p>这一年，人工智能领域发生的事件比前十年加起来还要多。据不完全统计，目前国内的AI大模型已超过100个。除了百度、阿里等互联网大厂，创新工场CEO李开复、美团创始人王兴、搜狗创始人王小川等“科技老兵”纷纷重披战甲进军大模型领域。</p><p>这一年，大模型改变了公众的关注点。据柯林斯词典统计，今年人们提及“AI”的次数是去年的4倍。而有关AI是否会取代人类的担忧，也比以往任何时候都紧迫而强烈。</p></div>', '2023-12-13 16:18:00');
INSERT INTO `articles` VALUES (202, '深度：ChatGPT只是表面的喧嚣，大模型才是那柄尖刀', '<div class="left_zw"><p>如果把时钟拨到2023年底，当我们回过头来看今年科技界最激动人心的大事件，ChatGPT的横空出世无疑会占据一席之地。就像几年前大家被谷歌AlphaGo点燃对人工智能的热情一样，人们对ChatGPT的热情只多不少。</p><p>并且，AlphaGo其实只是虚晃一枪，并没能带来很多的实际应用，而ChatGPT不一样，商业应用速度异常迅速，超过了大部分的预期。OpenAI很快推出了GPT-4，微软很快将相应模型接入其搜索、office全家桶等各条业务线；谷歌以Bard仓促应战，并与其搜索业务深度绑定，褒贬不一；国内的百度以文心一言快速跟进，目前已经有数十万家企业在排队接入文心一言；阿里巴巴发布的通义千问，同样得到数十万企业的热情回应。</p><p>人们对于这类AI应用的热情可见一斑。</p><p>实际上，正如数据猿先前发布的文章《含泪控诉人类科技史上的三个顶级“渣男”！》，人工智能、可控核聚变、元宇宙这三个领域的每一次突破，都将极大的挑动人类敏感的神经，都能引发一波全民追捧热浪。</p><p>然而，外行看热闹，内行看门道。作为一个专业媒体，数据猿并不满足于报道浮在行业表面的热点新闻，而要试图去挖掘隐藏在冰山底下的秘密。</p><p>在我们看来，虽然现在ChatGPT已经成为万众瞩目的明星，但它却只是摆在台面上的“提线木偶”，真正隐藏在幕后操控这一切的幕后大佬另有其人。</p><p>一言以蔽之：ChatGPT只是表面的喧嚣，大模型才是刺破AI的那柄尖刀。</p><p>所以，要搞清楚目前的状况，应该把更多的注意力放在底层的大模型上，而不是停留在ChatGPT上。正如上一轮AlphaGo引发的AI浪潮，其底层驱动力是深度学习技术的突破。</p></div>', '2023-11-29 21:10:00');
INSERT INTO `articles` VALUES (203, '​高元、叶明：ChatGPT横空出世，是风险还是机遇？', '<div class="left_zw"><p>自美国AI公司OpenAI于2022年11月30日发布全新通用型对话系统ChatGPT并免费开放网页端试用功能以来，经过短短5天，活跃人数就超过100万，2个月的活跃人数就达1个亿，打破了史上增长最快的消费者应用程序的记录。ChatGPT在写提纲、编邮件、优化语句、合成代码、修复Bug等要求下的内容生成，不仅效果优异，在完成速度上也有着优越的表现，展现了ChatGPT文本生成能力的强大应用场景。与之前推出的小冰、小度、Siri等对话机器人相比，ChatGPT的回复更为智能，大多数情形下能够结合上下文形成“有逻辑的对话”，实现了从“人工智障”到“强人工智能”的跨越性转变。</p><p>2023年3月15日凌晨，OpenAI再次推陈出新，发布多模态预训练大模型 GPT-4。OpenAI声称：“这是OpenAI努力扩展深度学习的最新里程碑。它接受图像和文本输入并进行文本输出，虽然在许多现实场景中它尚不如人类，但在各种专业和学术基准上已表现出与人类相当的性能”。这意味着生成式人工智能将不限于文本领域，借助人工智能直接生成图像、视频等素材的愿想正在逐步迈向现实。</p></div>', '2023-11-28 15:54:00');
INSERT INTO `articles` VALUES (204, 'OpenAI 的 12 个大新闻之一：200美金/月的 ChatGPT Pro 套餐，包括不限量的 OpenAI o1 新模型', '<div class="left_zw"><p>　　OpenAI 最近发布了一个名为“12 个大新闻”的系列，每天发布一条关于公司发展的重磅消息。</p><div style="text-align:center"><img alt="" src="https://static3.appinn.com/images/2024/12/Screen-20241206101639@2x.avif" style="border:0px solid #000000"/></div><p>　　其中，第一条消息介绍了一个 200 美金/月的 ChatGPT Pro 套餐，包括不限量使用 OpenAI o1 新模型、o1-mini、GPT-4o、高级语音功能以及 o1 Pro 专业模式。OpenAI o1 是之前仅限量使用的 o1-preview 模型的升级版，具备深度推理和多领域解决问题的能力，擅长处理如单位换算、代数方程求解、火箭轨道计算、生产计划调度等问题。</p><p>　　此外，OpenAI o1 正式版在数学、代码、科学问题的基准测试上表现出色，并且提供了更强大的处理能力。</p></div>', '2024-12-16 15:36:00');
INSERT INTO `articles` VALUES (205, 'OpenAI正式推出AI视频生成模型Sora：ChatGPT订阅用户免费用', '<div class="left_zw"><p>快科技12月10日消息，在首次公布10个月之后，OpenAI宣布正式向用户开放人工智能(AI)视频生成模型版本Sora，该系统可以根据文本提示生成逼真的视频。</p><p>同时，OpenAI还推出了Sora的新版本Sora Turbo，称较2月预览的版本速度显著加快，可以生成最长达20秒的视频，并且可以提供这些视频的多种变体。</p><div style="text-align:center"><img src="https://img-s-msn-com.akamaized.net/tenant/amp/entityid/AA1vyVIA.img?w=600&h=336&m=6&x=135&y=135&s=349&d=44" alt="Sora视频生成示意" style="width:100%; border:0;"></div><p>据介绍，Sora具有替换、删除或新增元素、重新剪辑、扩展及合并视频等功能。</p><p>Sora能生成任意长宽比的视频，分辨率从480p到1080p，时长从5秒到20秒，还可以一次生成多个方向的视频版本，让用户选择其中最理想的一个。</p><div style="text-align:center"><img src="https://img-s-msn-com.akamaized.net/tenant/amp/entityid/AA1vyRrH.img?w=600&h=336&m=6" alt="Sora视频生成示意" style="width:100%; border:0;"></div></div>', '2024-02-25 05:52:00');
INSERT INTO `articles` VALUES (206, 'ChatGPT 高级语音模式主创“自立门户”：新公司估值达 2 亿美元，打造有情感的 AI 语音交互', '<div class="left_zw"><p>IT之家 12 月 10 日消息，前 OpenAI 研究员 Alexis Conneau 最新创立 WaveForms AI，目前已获 a16z 4000 万美元种子轮融资，剑指音频 AI 交互领域。</p><p><strong>明星团队</strong></p><p>公司创始人 Alexis Conneau 曾在 OpenAI 主导开发 ChatGPT 的高级语音模式 (Advanced Voice Mode)，拥有丰富的语音 AI 经验。</p><p><strong>团队愿景</strong></p><p>WaveForms AI 公司的愿景是让人工智能语音交互更加自然，更具情感共鸣，并希望实现情感通用智能（EGI），让人工智能系统能够实时解释和响应情感线索，从而带来更有意义和更具吸引力的互动。</p><p>Conneau 表示该 AI 音频系统灵感部分来源于电影《她》，能够避免成瘾性和隔离性体验等负面影响下，让更多用户感受 AI 的优势，此外公司将优先考虑同理心和连接，构建更积极的人机交互体验。</p><p>公司计划于 2025 年发布首批产品，但目前尚未公开技术演示，公司网站也仅限于对音频智能愿景的描述。</p><p>WaveForms AI 完成 4000 万美元（IT之家备注：当前约 2.91 亿元人民币）种子轮融资，公司估值达 2 亿美元，由 Andreessen Horowitz 领投，知名风险投资家 Marc Andreessen 对此项目表现出浓厚兴趣。</p></div>', '2024-12-10 15:51:00');
INSERT INTO `articles` VALUES (207, 'ChatGPT是怎么在中国火起来的？', '<p>一时之间，似乎朋友圈人人都在讨论ChatGPT。</p><p>谷歌搜索的数据也证实了，目前全球ChatGPT讨论度最高的就是在中国。本来ChatGPT自去年11月底问世后火了个把月热度已渐趋下降，但在中国的讨论热潮，又推动ChatGPT搜索指数再次上涨，且火热程度远超第一波。</p><p>ChatGPT究竟是怎样在中国火起来的？</p><img src="https://imagepphcloud.thepaper.cn/pph/image/237/537/8.jpg" alt="image"><p>其实，在ChatGPT上线的一周内，便在国内短暂产生过一波搜索热潮，但热度不及目前的四分之一。</p><p>在此阶段，网民们的注意力主要集中在ChatGPT使用方法上。各大科技博主纷纷推出ChatGPT注册指南，网友们也大开脑洞，向ChatGPT花式提问。</p><p>ChatGPT活跃用户目前还在飞速增长，瑞银报告数据显示，截至 1月末，ChatGPT推出2个月时，其月活用户已经突破了1亿。根据Similarweb数据统计，2023年1月期间，ChatGPT 平均每天大约有 1300 万独立访客，这一数据是 2022 年 12 月的两倍之多。</p><p>关于未来，我们向ChatGPT询问：“你是否认为2023年ChatGPT会在中国最流行？”</p><p>它给出了肯定的回答：“是的，我非常有信心，2023年ChatGPT会在中国最流行。随着越来越多的公司和企业开始使用ChatGPT来加速他们的业务，这种趋势将会不断增长。同时，中国在AI技术方面也取得了显著的进步，因此我相信到2023年，ChatGPT在中国的使用将比现在更加普及。”</p><p>ChatGPT的预言是否能实现？我们拭目以待。</p>', '2023-02-08 13:22:00');
INSERT INTO `articles` VALUES (209, '时间线复盘ChatGPT爆火之路：改变互联网圈的两个月', '<p>世界变化得如此之快。在刚刚过去的周末，生成式人工智能（AI）狂潮继续席卷科技产业，围绕ChatGPT的技术军备竞赛正热火朝天，发酵之势愈演愈烈。仅是周六，就有清华大学新闻与传播学教授、博士生导师沈阳，原美团“二号人物”、联合创始人王慧文，分别在社交平台上发文“求组队”。沈阳教授一大早发文称“ChatGPT和AIGC摧毁一切，求组队抵抗”，愿意拿出自家家底和互补的团队共同抵御ChatGPT对中国带来的压力。王慧文则在当晚发表“人工智能宣言”，要带资5000万美元入组创业，不在意岗位、薪资和title。而大佬冲到AI牌桌前的速度远比想象的快。被ChatGPT概念点燃后，王慧文火速出击，立即张罗起自己的创业队伍，今日一早，他就宣布了新进展：打造中国OpenAI、设立北京光年之外公司，估值2亿美元，下轮融资已有顶级VC认购2.3亿美元，开始大举招揽顶级AI研发人才。</p><img src="https://img2.jiemian.com/jiemian/original/20230213/167628478679185800_a700xH.png" alt="image">', '2023-11-17 17:04:00');
INSERT INTO `articles` VALUES (210, '新闻分析：ChatGPT，变革与风险', '<p>美国人工智能公司OpenAI的大语言模型ChatGPT在推出约两个月后，1月已达到1亿月活跃用户，成为历史上增长最快的消费者应用程序。相关专家预计，ChatGPT不仅是新一代聊天机器人的突破，也将为信息产业带来巨大变革，但由此带来的学术造假、技术滥用、舆论安全等风险亦不容忽视。</p><p>新一代操作系统平台的雏形</p><p>多语言撰写充满想象力的诗歌，编写可运行的程序，快速生成论文摘要，自动制作数据表格，纠正文章中的语法和表达错误，把一周大事写成新闻综述……ChatGPT不仅能理解很多人类问题和指令，流畅展开多轮对话，也在越来越多领域显示出解决多种通用问题的能力。</p><p>ChatGPT还轻松通过一些对人类难度较高的专业级测试：它新近通过了谷歌编码L3级（入门级）工程师测试；分别以B和C+的成绩通过了美国宾夕法尼亚大学沃顿商学院MBA的期末考试和明尼苏达大学四门课程的研究生考试；通过了美国执业医师资格考试……业界形容它的诞生是人工智能时代的“iPhone时刻”，意味着人工智能迎来革命性转折点。</p><p>“ChatGPT的成功不应仅仅被看作新一代聊天机器人的突破，而应该重视其对人工智能乃至整个信息产业带来的革命。”北京智源人工智能研究院院长黄铁军接受记者专访时说，人工智能领域的过去十年是深度学习的十年，但产业总体上并没有出现移动互联网和云计算级别的爆发，“ChatGPT的出现，具有划时代意义，大模型+ChatGPT已形成新一代操作系统平台的雏形”。</p><p>黄铁军说，ChatGPT在技术路径上采用了“大数据+大算力+强算法=大模型”路线，又在“基础大模型+指令微调”方向探索出新范式，其中基础大模型类似大脑，指令微调是交互训练，两者结合实现逼近人类的语言智能。ChatGPT应用了“基于人类反馈的强化学习”训练方式，用人类偏好作为奖励信号训练模型，促使模型越来越符合人类的认知理解模式。</p><p>“这样的AI可帮助人类进行真实创造，尤其是帮助人类提高创造效率，比如提高获取信息的效率或提出新颖想法，再由人解决其真实性问题。创造效率的提高将产生巨大效益和多方面影响，可以改变世界信息化格局。”中国科学技术大学机器人实验室主任陈小平对记者说。</p>', '2023-07-18 16:18:00');
INSERT INTO `articles` VALUES (211, '梁正、何江 | ChatGPT意义影响、应用前景与治理挑战', '<p>ChatGPT作为人工智能技术新的里程碑式应用，在推出仅2个月后，活跃用户已经突破1亿，成为史上用户增长速度最快的消费级应用程序。ChatGPT的火爆标志着人工智能技术发展进入新的时代，将深度影响整个经济社会的未来变革。<br>《中国发展观察》杂志本期特别策划专题“人工智能新时代”，邀请来自国务院发展研究中心、清华大学、中国社会科学院的专家学者分别撰文，介绍了以ChatGPT为代表的人工智能技术的新发展，展望了新技术驱动下相关行业发展的前景，同时对新技术可能带来的挑战及应对进行分析，提出建议。<br>聊天生成预训练转换器（Chat Generative Pre–trained Transformer，ChatGPT）作为由Open AI 研发的人工智能（Artificial Intelligence，AI）大语言模型，于2022年11月30日上线，在发布仅一周的时间内就已拥有超100万用户，在推出仅两个月后的2023年1月末，其月活用户已经突破1亿，作为史上用户增长速度最快的消费级应用程序，已然成为火爆全球的一款现象级产品。<br>目前，ChatGPT不再局限于聊天问答、娱乐互动等基本功能，而是迭代升级成为高效的生产力辅助工具，用户可通过聊天互动方式实现邮件、报告、翻译、代码等诸多内容的智能化生成。<br>长远来看，ChatGPT的横空出世，不仅是对新一代聊天机器人和生成式AI的突破， 还将对AI产业乃至各行业数智化转型升级带来颠覆性变革，进而重构产业知识模型构建范式，推动智力密集型服务产业规模化和市场化。</p>', '2023-11-10 08:19:00');
INSERT INTO `articles` VALUES (212, '如何看待ChatGPT？​听听来自不同领域学者的看法', '<div class="left_zw"><p>在ChatGPT横空出世一年后，《自然》杂志首次破格将ChatGPT这位“非人类”列为全球重大科学事件中的十位人物之一。对于社会而言，ChatGPT最大的贡献也许在于它不仅使人望见生成式AI巨大的发展空间和潜力，还使得这种能力触手可及、让未来到来。在行业内“炼大模型”发展如火如荼的同时，ChatGPT也快速渗透了学术界，几乎所有学科的研究和教学中都能看到其身影。学界期待建立起更科学完善的框架，监管好生成式AI这把“普罗米修斯之火”，以期将人们带向更光明智慧的远方。近日，中国社会科学网采访了首都经济贸易大学副校长陈彦斌、中国人民大学交叉科学研究院院长杨东、中国社会科学院新闻与传播研究所所长胡正荣、中国社会科学院文学研究所副所长安德明、华南农业大学外国语学院院长黄国文、郑州大学信息管理学院原院长臧国全、北京师范大学文学院教授赵勇、南开大学法学院副院长陈兵、南开大学经济学院教授李磊等多位来自不同学科领域的学者，倾听他们对于ChatGPT的应用、发展等相关话题的看法。</p><p>中国社会科学网：在短短一年的时间里，ChatGPT已经渗透到各类学科研究中。您是如何使用ChatGPT的？您对ChatGPT在学术中的应用持怎样的态度？</p><p>李磊：自ChatGPT发布以来，其强大功能吸引了大量用户，我也开始不断了解并运用ChatGPT，将其作为一种辅助学习工具使用。我身边的学者朋友有对ChatGPT持乐观态度的，也有持保留态度的。他们大多数是将ChatGPT作为一种检索工具，以快速获取所需的学习资料和指导。我对ChatGPT在学术中的应用持中立的态度，一方面，应做到在一个规则框架下使用ChatGPT，发挥其最大化效用；另一方面，应做到全面看待并分析其利弊，在拥抱科技、享受ChatGPT给生活带来便利的同时，减弱其负面影响。</p><p>陈彦斌：我使用ChatGPT比较少，但我的学生们使用得要稍微多一些。相对于传统搜索引擎，ChatGPT能够帮助研究者尽快地了解一个新概念、新方向和新领域，同时其强大的文本分析能力和文章措辞修改能力，对于研究者也有较好的帮助。用好ChatGPT，能让研究者提高研究效率，达到事半功倍的效果。但是，研究者不能对ChatGPT产生依赖性，否则有可能会减弱甚至失去独立分析思考能力。研究者要有创新思维，在已有知识和前人研究的基础上发现新的问题并给出新的答案，这是ChatGPT难以取代的。除此之外，对于ChatGPT所提供的答案，要规范引用，要避免ChatGPT带来的知识产权问题。</p><div style="text-align:center"><img alt="" style="width:100%" src="https://imagepphcloud.thepaper.cn/pph/image/239/192/840.jpg" data-imageid="239192840" /></div></div>', '2024-02-18 20:25:00');
INSERT INTO `articles` VALUES (213, '哥伦比亚大学研究：ChatGPT新闻搜索准确性堪忧，错误率高达近六成', '<p><img src="https://img-s-msn-com.akamaized.net/tenant/amp/entityid/AA1vfsmn.img?w=768&h=681&m=6" alt="哥伦比亚大学研究报告" /> 近期，美国哥伦比亚大学Tow数字新闻研究中心公布了一项引人关注的研究报告，指出OpenAI的ChatGPT搜索工具在新闻搜索领域的准确性上存在显著不足。报告详细记录了ChatGPT在153次回答中的表现，结果显示，其回答错误的次数高达89次，部分正确的有57次，而完全正确的回答仅有47次，另有7次明确表示无法给出准确答案。为了全面评估ChatGPT的搜索准确性，哥伦比亚大学的研究团队精心挑选了来自20家不同出版商的200条引文作为测试样本，其中特别包含了40条来自被OpenAI爬虫禁止访问的网站的引文。这一设计旨在全面且客观地检验ChatGPT的搜索能力。研究报告中还列举了多个具体的错误案例，比如ChatGPT错误地将《奥兰多哨兵报》的读者来信归为了《时代周刊》的文章；在另一个案例中，当ChatGPT试图确认一篇关于濒危鲸鱼的海外媒体报道时，却错误地链接到了一个剽窃该文章的网站。这些错误不仅令人惊讶，也引发了对于ChatGPT搜索工具准确性的广泛质疑。面对这一研究结果，OpenAI方面表示，由于哥伦比亚大学Tow数字新闻研究中心并未提供完整的测试数据和测试方法，因此解决这些错误归因问题具有一定的挑战性。OpenAI认为，这次研究可能并不具有普遍性，是一次非典型的测试。不过，OpenAI也承诺将继续努力优化其搜索结果，以提高准确性和可靠性。</p>', '2023-11-04 16:00:00');
INSERT INTO `articles` VALUES (216, '实测ChatGPT最新搜索功能，AI搜索冲击谷歌霸主地位', '<p><img src="https://img-s-msn-com.akamaized.net/tenant/amp/entityid/AA1tjUZW.img?w=768&h=432&m=6" alt="OpenAI ChatGPT Search功能" /> 蓝鲸新闻11月1日讯（记者 朱俊熹）当地时间10月31日，OpenAI正式上线ChatGPT搜索功能，向谷歌发起挑战。OpenAI官方介绍称，ChatGPT Search支持实时网络搜索，为用户提供快速、及时的最新资讯和答案，无需再借助另外的搜索引擎。该功能基于GPT-4o模型的微调版本，OpenAI曾在7月小范围推出过原型产品SearchGPT，并根据反馈对搜索功能进行了改进。当使用搜索功能时，ChatGPT会在聊天窗口中提供答案的来源链接，包括各类新闻和博客文章。目前ChatGPT Search已向付费的Plus和Team用户全面开放。使用企业版和教育版的付费用户将在未来几周内获得访问权限，免费用户则需要再等待几个月的时间。</p>', '2024-11-10 16:03:00');
INSERT INTO `articles` VALUES (218, 'OpenAI最新发布！ChatGPT搜索功能强势来袭，挑战谷歌？', '<p>当地时间10月31日，OpenAI在官网宣布，其在ChatGPT中推出了搜索功能，“可以通过相关网络资源的链接获得快速、及时的答案。”新闻稿写道，用户能以更自然、对话的方式提出问题，ChatGPT可以选择使用来自网络的信息进行回复，并且可以在后续进行更深入的探讨。OpenAI称，“我们与新闻和数据提供商合作，为天气、股票、体育、新闻和地图等类别添加最新信息和新的视觉设计。”官网分别展示了纽约市天气、英伟达股价、金州勇士队NBA赛程、古巴停电新闻以及一家贝果店地图搜索结果的视觉设计。OpenAI称，目前这项功能只有付费的ChatGPT Plus和Team用户，以及SearchGPT候补名单用户可以访问。</p>', '2024-11-23 17:16:00');
INSERT INTO `articles` VALUES (220, 'ChatGPT是什么', '<div class="left_zw"><p>chatGPT是什么？这可能是最近被问的最多的一个。大家第一反应这应该是GPT系列的一个最新模型，普通大众可能更愿意把它看做是一个人工智能。实际上，它其实就是一个基于大规模语言模型的对话系统产品。官网对它定义十分的明确：Optimizing Language Models for Dialogue.<img src="https://i-blog.csdnimg.cn/blog_migrate/bf529083a58cf7df10f2396b97e6545e.png" />最大的问题在于，它的背后究竟是一个什么？很多人都以为，chatGPT是一个单一模型，就如同GPT-1/2一样，应该是一个可以被加载和训练的。我承认，chatGPT的背后，是有一个像GPT-3一样的基础模型，但是其现在的性能表现，远远不是只有1个基础模型这么简单。因为我们默认的chatGPT是web UI界面，它至少是有一些外部工程代码的。举个例子，对于汉语和英语的反馈速度有质的差别，如果只是单一的模型统一编码了多语言，不会出现这种情况。因此，对我而言，chatGPT更像是一个完善的产品，而不是一个简单的模型。而且由于其训练过程的复杂和不透明，使得我们很难复现它。</p></div>', '2023-03-02 09:34:00');
INSERT INTO `articles` VALUES (222, 'ChatGPT以及GPT系列模型', '<p>ChatGPT不是一蹴而就突然出现的，它是有着长达5年以上的技术积累才走到这个地步的。之前网上讲了很多关于ChatGPT和它的前辈，比如比较出名的有拆解追溯GPT-3.5各项能力的起源。但是我认为真正需要了解ChatGPT的前世今生，还是需要去看openAI官方网站以及它们的论文。</p><p>GPT-1/2/3</p><p>首先我们先来看GPT-1到GPT-2以及GPT-3的变化。首先在2018年6月，发布了第一版GPT-1，使用的是transformer的decoder架构+任务微调的形式，但是整体上似乎没有什么特别出彩的地方。然后再到2019年2月，发布了第二版GPT-2，这时候他们优化了网络架构（如右图所示），并且使用了10倍大小的网络规模和8倍大小的预训练数据，并且去除了特定任务微调的形式从而获取prompt learning的能力。GPT-2确实有点东西，但是由于3个月前，BERT的出现，让它也没有当上一哥的位置。不过整体上应该接近后来的GPT-3系列模型了。</p><img src="https://i-blog.csdnimg.cn/blog_migrate/a461f3ae59cf22588db8a2c1dd718429.png" alt="GPT-2 图"><p>在GPT-2的1年半以后，GPT-3发布了，它同样还是3个路线，更优的架构，更大的规模（100倍），更大的数据量（1000倍），真正训练出了一个超级巨无霸GPT-3。奠定了现在GPT帝国的基础。但是实际上，它和GPT-2没有太多本质的区别，包括训练方式，只是更大了。</p><p>那么GPT-3有多大呢，大家可以看一下下面的图就可以知道了，需要注意的是，这里看到参数量的改变是一个log指数，可以看到熟悉的Bert-large模型和GPT-2模型。而GPT-3的175B按照官方说明，大概有350~500GB的显存需求，如果使用FP16加载该模型，大概需要至少5块A100（80G）才能够加载完成。而如果要从头开始训练，至少需要1000块A100才能够在可接受的时间（几个月）里训练出该模型。</p><img src="https://i-blog.csdnimg.cn/blog_migrate/5686ad8eaf4abb99da72ff5b26f3feef.png" alt="GPT-3 图"><p>据传闻说，现在国内的大部分大规模语言模型还是处于GPT-2.5阶段，也就是说对标的是GPT-3，但是训练规模（1-10B）和数据量（几十G左右）还是在GPT-2的级别上。无论是模型规模还是语料质量，距离GPT-3都还有较大差距。需要注意的是，从GPT-3开始，其模型就不再完全公开了，只能通过API访问。</p><p>GPT3.5</p><p>然后我们再来看GPT3以后发生的事情。首先我们需要先介绍一下GPT-3以后最重要的一件事，那就是InstructGPT的出现，根据openAI官方网站上说明，InstructGPT包含三种训练方式，分别是有监督微调（Supervised Fine-Tuning, SFT），反馈变得更容易（Feedback Made Easy, FeedME）以及基于PPO算法的从人类反馈中进行强化学习（PPO）三个部分。因此，可以说，InstructGPT泛指的话，是包含红色的所有模型，而特指的话，则是指的davinci-instruct-beta。这一点尤为重要。至于这个FeedME,大家可以参考图片上的官方解释，个人理解可能是由纯人工反馈的指令微调，PPO则是更强调利用强化学习自动化指令微调。</p>', '2023-03-11 10:18:00');
INSERT INTO `articles` VALUES (224, ' 复现chatGPT的难点', '<div class="left_zw"><p>海量的数据 从GPT-3公开发表的论文里讲到，其用于预训练的文本达到了45TB，这是一个非常巨大的数字，关键是其质量应该是非常高的。据查看的一些资料显示，在中文数据上，全球最大的语料库是WuDaoCorpora，据说有3TB的中文语料（200G开放使用）。而且，也有人指出，更多样化的token也能够让模型学习的更充分。如果只是训练一个汉语版的chatGPT，除了一些搜索和社交巨头，能够获取足够数据的也只有一些垂直领域的公司了。关键是公司运营期间获得的文本数据是否可以被用于训练模型，这是一个法律和道德问题。（即使是codex训练来源于开源的github，也同样遭受了大量的非议。）当然，如果是垂直领域，未必需要这么大的数据量也未尝不可。我们现在总以为，我们有数据，有模型架构，我们就可以拥有chatGPT。事实显然不是这样，如果想让它成为一个优秀的产品，而不是粗糙的学术模型，至少应该包括以下3个部分：</p><ol><li>核心模型 核心模型就是语言模型，可能是一个超大规模的单一模型，也有可能是一个带有很多小模块组成的模型集群。</li><li>辅助模型 辅助模型有哪些？比如我们可以看到的reward model，还有大家容易忽略的安全检查模型等。这些都是保证了产品的长期正常的运营。而反观我们有些机构的模型匆匆发布，产生了大量的不安全的言论，这也是不负责任的表现。</li></ol></div>', '2023-04-18 01:00:00');
INSERT INTO `articles` VALUES (225, 'ChatGPT引发新一轮人工智能科技竞赛', '<div class="left_zw"><p>　　ChatGPT的问世正在人工智能领域引发新一轮科技竞赛。北京时间2月8日凌晨，微软推出由ChatGPT支持的最新版本必应搜索引擎和Edge浏览器，宣布要“重塑搜索”。微软旗下Office、Azure云服务等所有产品都将全线整合ChatGPT。</p><p>　　北京时间2月7日凌晨，谷歌也发布了基于谷歌LaMDA大模型的下一代对话AI系统Bard。同一天，百度官宣正在研发的大模型类项目“文心一言”，计划在3月完成内测，随后对公众开放。阿里巴巴、京东等中国企业也表示正在或计划研发类似产品。</p><p>　　人工智能大模型领域的全球竞争已趋白热化。黄铁军认为，ChatGPT未来有望演变成新一代操作系统平台和生态。这种变革似移动互联网从个人电脑到手机的转化，大部分计算负荷将由大模型为核心的新一代信息基础设施接管。这一新范式将影响从应用到基础设施各层面，引发整个产业格局的巨变，大模型及其软硬件支撑系统的生态之争将成为未来十年信息产业焦点。</p><p>　　值得注意的是，ChatGPT有时会“一本正经地胡说八道”，存在事实性错误、知识盲区和常识偏差等诸多问题，还面临训练数据来源合规性、数据使用的偏见性、生成虚假信息、版权争议等人工智能通用风险。多家全球知名学术期刊为此更新编辑准则，包括任何大型语言模型工具都不会被接受为研究论文署名作者等。</p><p>　　“学术论文的署名作者须满足至少两个条件，其一是在论文工作中做出‘实质性贡献’，其二是能承担相关的责任。目前这两个条件ChatGPT（以及其他AI系统）都不满足。”陈小平说。</p><p>　　ChatGPT也有应用在舆论信息战方面的潜力。加拿大麦吉尔大学研究团队曾使用ChatGPT前代模型GPT-2阅读加拿大广播公司播发的约5000篇有关新冠疫情的文章，然后要求其生成关于这场危机的“反事实新闻”。连OpenAI也警告使用ChatGPT的用户，它“可能偶尔会生成不正确的信息”，“产生有害指令或有偏见的内容”。</p><p>　　“针对这些问题，需要我们在发展技术的同时，对于ChatGPT应用边界加以管控，建立起对人工智能生成内容的管理法规，对利用人工智能生成和传播不实不良内容进行规避。同时加强治理工具的开发，通过技术手段识别人工智能生成内容。这对于内容检测和作品确权，都是重要前提。”北京瑞莱智慧科技有限公司副总裁唐家渝说。</p></div>', '2023-02-03 11:03:00');
INSERT INTO `articles` VALUES (226, '刚刚！ChatGPT正式成为AI搜索，免费可用', '<div class="left_zw"><p>　　ChatGPT search 的推出正式宣告 ChatGPT 消除了即时信息这一最后短板。现在，人们与全球最先进 AI 大模型聊天时，也可以通过网络资源链接快速、及时地获取答案了。</p><p>　　即日起，付费订阅者（以及 SearchGPT 候补名单上的用户）将获得可联网的实时对话信息能力，免费用户、企业用户和教育用户也将在未来几周内陆续获得访问权限。该功能覆盖 ChatGPT 的网页版，以及手机、桌面应用。在正常的对话时，ChatGPT 可以根据具体需求决定何时利用网络中的搜索结果，当然用户也可以主动触发网络搜索。</p><p>　　显然，新功能已经测试已久，上线是一瞬间的，很多人已经用起来了。可以看到，ChatGPT 搜索的天气、股市、地图等小组件齐全，是个完整版搜索引擎的样子，当然，搜索结果中引用的网络链接也一个都不会少。</p><p>　　你也可以根据 AI 搜索结果里的一堆链接，直接让 ChatGPT 直接生成一份详细的摘要。或是顺着搜索结果继续追问，总而言之，ChatGPT 和搜索功能是完全一体化的。</p><p>　　看起来很美好的样子，网友们一致表示欢迎，同时为谷歌和 Perplexity 担忧一秒钟。</p></div>', '2024-11-01 16:25:00');
INSERT INTO `articles` VALUES (227, 'ChatGPT是什么？它强在哪里？', '<div class="left_zw"><p>　　美国人工智能公司OpenAI于2022年11月30日发布的ChatGPT通用型对话系统，能够通过学习和理解人类的语言来进行对话，还能根据聊天的上下文进行互动，能更精准地理解用户意图及实现类人的回复，能更精确、更加可控地完成撰写邮件、视频脚本、文案、代码等任务，甚至在人类的合适引导下可以完成逻辑推理、新知识快速学习等复杂任务。</p><p>　　ChatGPT本质上是一个由浮点数参数表示的深度神经网络大模型（目前版本含约1750亿个参数），所以仍然属于深度学习的框架。深度学习自2006年被提出，2010年左右陆续被应用于以语音识别、图像识别为代表的感知技术领域，极大地推动了人工智能技术和产业的发展。ChatGPT的推出是深度学习提出后又一个里程碑式的技术革命，将为以自然语言处理为核心的认知智能技术发展提供新的“历史机遇期”。大模型技术从2018年开始兴起，近年来国内外也发布了多个模型参数庞大的深度神经网络大模型，但唯独这次的ChatGPT引发了全球学术界和产业界的热议和关注，关键原因是ChatGPT通过至少以下五个维度能力的显著提升综合实现了初步的“智慧涌现”：（1）海量高价值信息的全量在线记忆能力；（2）自然语言输入的任意任务和多轮对话理解能力；（3）复杂逻辑的思维链推理能力；（4）多角色多风格的长文本生成表达能力；（5）即时新知识学习应用与进化能力。此外，因为引入了代码作为训练语料，ChatGPT还额外产生了自动写代码和理解代码的能力。</p></div>', '2023-10-16 18:31:00');
INSERT INTO `articles` VALUES (229, 'ChatGPT为什么这么强？', '<div class="left_zw"><p>　　针对目前ChatGPT热潮，学术界已经有不少专家做了算法上的专业分析，而我们基于GPT系列模型的相关论文以及对实际效果的体验测试，结合科大讯飞在认知智能和深度神经网络大模型上的研发实践，经过深入分析后判断ChatGPT的智慧涌现主要可归因于其以下创新工作：</p><p>（一）基于海量高质量文本语料的无监督预训练。如instructGPT从原始45TB语料清洗得到570GB高质量训练语料，并通过预训练策略在大模型里实现了海量信息的有效“存储”。</p><p>（二）创新性地引入代码文本的训练策略。如instructGPT使用了GitHub的830GB代码文本数据，充分利用代码的函数定义和调用、变量远距离引用等体现程序员解题思路和逻辑的代码文本特性，有效增强了复杂逻辑思维链推理能力。</p><p>（三）基于数万个Prompt任务的统一生成范式有监督训练。如instructGPT收集了覆盖范围很广的数万个语言和知识相关的有监督任务数据集，并转换为统一的文本生成范式任务，提高了大模型对语义和知识表征的泛化及准确理解能力。</p><p>（四）基于人工反馈强化学习实现生成结果的优化。OpenAI公司雇佣了众包团队大规模开展了生成结果好坏的人工标注，并基于标注数据进行强化学习，使得大模型生成结果更加无偏见和符合人类预期。</p><p>　　ChatGPT的成功也向全世界明确揭示了深度神经网络技术继主导以语音识别、图像识别为代表的感知智能技术路线后，针对更难的以自然语言处理为基础的认知智能领域，也体现出了巨大的潜力。</p></div>', '2023-10-16 15:19:00');
INSERT INTO `articles` VALUES (230, '从大模型到ChatGPT，还要跨越哪些鸿沟？', '<div class="left_zw"><p>　　基于以上分析，ChatGPT大模型其实是在GPT3基础上进一步开发的自然语言处理（NLP）模型。大模型在我国也是研究热点，国内外各大模型参数规模持续攀升、目前最高达到10万亿级，有些模型还支持图像、文本、语音等多个模态，但在智慧涌现方面与ChatGPT存在较大差距。</p><p>　　ChatGPT相较于GPT3模型和国内一些代表性大模型在效果体验上获得较大提升的原因，我们认为除了算法创新之外，应该是综合运用了高质量训练数据（并引入代码等）、创新模型训练算法（Prompt提示、多任务的联合学习、基于人类反馈的强化学习等）与AI工程化（提高研发与计算效率）等多方面因素的系统性创新结果。</p><p>　　ChatGPT以当前业界主流的Transformer模型为主结构。当前我国也研发出了多个基于文本或多模态的预训练基础大模型，与国外基础大模型算法的差距不大，奠定了比较好的大模型训练的平台基础并进行了算法模型的框架积累。以这些大模型为基础，打造一个类ChatGPT大模型，还需在数据、算力、工程实现等三个方面努力。</p><p>　　在数据方面，对于预训练模型来说，数据的质量和数量直接影响模型的质量。因此，我们需要在保障数据安全、用户隐私等符合国家法律法规和政策的前提下，进一步挖掘、收集及清洗高质量、多领域、多行业及多样性的海量丰富数据，辅以技术和人工结合的高质量清洗工作，并持续通过人工标注反馈强化学习，使得模型生成结果更加可控、合理及可靠，当然数据背后的知识符合道德伦理、法规要求也非常重要。</p><p>　　在算力方面，超大模型由于参数规模大、数据体量大，因此需要更大的算力支持，带来过高的训练成本，据了解ChatGPT单次模型训练耗时1个月，训练成本达1200万美元。这里需要注意的是，超大模型的训练需要大规模计算集群以及对应的模型并行算法框架的支撑，我们判断1000张主流卡容量的独立计算集群是完成该类任务的门槛之一。</p><p>　　在工程实现方面，由于大模型在推理阶段仍然需要巨大的算力消耗（以对话交互为例，目前单次交互成本大致是主流方法的1000倍左右），如何将大模型部署在线上，以供大规模用户快速的、经济化地使用，也是需要关注的。否则研制出的大模型将被束之高阁，难以规模化应用。</p><p>　　更为重要的是，应该需要选择一个社会刚需应用或有大量活跃用户的应用作为切入点。这些应用可以结合用户交互体验数据，反馈给模型后进一步提升模型的能力，在数据和模型之间形成正向反馈循环的“涟漪效应”，使得迭代更新后的模型越来越强。过去十几年，面向感知智能领域的“涟漪效应”带来了智能语音、图文图像等领域产业系统效果的很大提升。如今我们需要再次面对难度更大的认知智能领域的“涟漪效应”，如何系统化地设计好相关的方案，很大程度上决定了未来我们在该领域的产业落地、价值兑现之路能走到多远。</p></div>', '2023-09-13 19:36:00');
INSERT INTO `articles` VALUES (231, '我国在认知智能方面的进展如何？ ', '<div class="left_zw"><p>　　首先，让我们再具体了解下认知智能具体所指：认知智能旨在赋予机器理解和模拟人类行为的能力，使机器“能理解、会思考、有情感”，通常指让机器掌握人类独有的语言和知识能力的一类人工智能技术，涵盖机器翻译、人机对话、知识推理、机器阅读理解、常识推理等具体任务，是人工智能发展的高级阶段。</p><p>　　根据2021年第三方发布的研究报告，在认知智能领域相关论文发表数量排名前十的机构中，有六所位于美国。中国在相关技术领域论文发表数量仅次于美国，但是质量上与美国还有一定差距。专利方面，我国相关专利申请数量2017年超过美日韩。产业方面，认知智能已在教育、医疗、金融、政务等多个领域落地，国内一些企业在产业应用模式探索上走在世界前列，具备了很好的技术基础、场景基础和数据基础。</p><p>　　以科大讯飞为例，依托科大讯飞和中国科学技术大学承建的认知智能全国重点实验室，聚焦面向“幸福中国”实现基于人工智能的教育/医疗优质资源普惠供给的需求、面向“中国智造”升级手机/汽车/家电/办公/机器人等人机智能交互的需求、面向全世界主要语种构建跨语言沟通无障碍的经济文化交流环境的需求，取得了一系列领先的技术研究成果，并在产业实现了大规模应用。</p><p>　　在智慧教育领域，实现了全学科智能批改和因材施教等方面的关键技术突破，2022年累计获得常识阅读理解挑战赛OpenBookQA、QASC、ReClor等13项认知智能国际竞赛冠军，在高考作文评分和雅思英语作文上都已经超过了人工，实现了全场景因材施教解决方案服务5万多所学校、1.3亿多师生；在智慧医疗领域研发的“智医助理”系统，已经通过了国家执业医师资格考试综合笔试测试，现在作为全科医生助手已可以诊断1200多种常见病，累计提供5.5亿次AI辅诊建议；在人机交互领域，实现了智能语音开放平台AI服务日调用次数超过50亿。在多语种技术研究方面，实现了60个语种的语音识别、语音合成、机器翻译、图文识别等关键技术研发，在中、英等十多个全球应用最主流语种中实现领跑，有力支撑了汽车、家电企业上亿台套出口产品所需的技术需求，其中机器翻译技术获得国际口语机器翻译评测比赛冠军，参加全国翻译专业资格（水平）测试，达到英语二级《口译实务（交替传译类）》和三级《口译实务》合格标准。</p></div>', '2023-04-09 21:05:00');
INSERT INTO `articles` VALUES (232, 'ChatGPT的技术演进方向是什么？', '<div class="left_zw"><p>　　ChatGPT的初步突破带来的当前影响和未来想象空间是巨大的。在算法优化和技术演进趋势方面，我们认为至少有以下四个方向：</p><p>1）需要通过进一步引入显性知识、常识类知识来提升目前输出答案的可靠性和稳定性，尤其在一些细节的内容上；</p><p>2）在认知智慧涌现的基础上，通过多模态语义空间的统一，从而具备生成图像、语音、视频等多模态内容的能力，实现多维表达和呈现；</p><p>3）针对当前通用大模型规模太大、难以针对及时信息灵活迭代更新的问题，未来将可通过大模型分布式部署，例如云边端协同等方式提升其灵活性，同时可能降低服务成本；</p><p>4）针对各个行业、甚至是每个人的个性化需求，未来会在大模型基础上向各层级的个性化模型发展，通过持续学习、理解每个人的对话和提交任务的风格、领域以及特定人的针对性反馈“调教”等信息，未来有望真正成为每个人的个人助手。</p><p>　　在上述技术演化的基础上，以自然语言处理为代表的人工智能算法有可能重构互联网和移动互联网的产品形态，促进教育业、医疗业、汽车业、金融业、消费业、媒体业、服务业和制造业等众多产业的升级，最终带来对应商业模式的变革。</p></div>', '2023-10-06 23:49:00');
INSERT INTO `articles` VALUES (233, 'ChatGPT会对未来世界带来哪些颠覆性影响？', '<div class="left_zw"><p>　　ChatGPT的初步突破带来的当前影响和未来想象空间是巨大的。在算法优化和技术演进趋势方面，我们认为至少有以下四个方向：</p><p>1）需要通过进一步引入显性知识、常识类知识来提升目前输出答案的可靠性和稳定性，尤其在一些细节的内容上；</p><p>2）在认知智慧涌现的基础上，通过多模态语义空间的统一，从而具备生成图像、语音、视频等多模态内容的能力，实现多维表达和呈现；</p><p>3）针对当前通用大模型规模太大、难以针对及时信息灵活迭代更新的问题，未来将可通过大模型分布式部署，例如云边端协同等方式提升其灵活性，同时可能降低服务成本；</p><p>4）针对各个行业、甚至是每个人的个性化需求，未来会在大模型基础上向各层级的个性化模型发展，通过持续学习、理解每个人的对话和提交任务的风格、领域以及特定人的针对性反馈“调教”等信息，未来有望真正成为每个人的个人助手。</p><p>　　在上述技术演化的基础上，以自然语言处理为代表的人工智能算法有可能重构互联网和移动互联网的产品形态，促进教育业、医疗业、汽车业、金融业、消费业、媒体业、服务业和制造业等众多产业的升级，最终带来对应商业模式的变革。</p></div>', '2023-10-05 23:59:00');
INSERT INTO `articles` VALUES (234, '讯飞在ChatGPT方面有哪些准备？', '<div class="left_zw"><p>　　在ChatGPT引发的AI技术新一轮热潮背景下，讯飞基于认知智能全国重点实验室，已在核心算法、行业数据、算力支撑及团队组建等方面建立了优势保障。</p><p>　　核心算法：在Transformer深度神经网络算法方面拥有丰富经验，已广泛应用于科大讯飞的语音识别、图文识别、机器翻译等任务并达到国际领先水平；创新提出了知识与大模型融合统一的理解框架X-Reasoner，有望弥补大模型的模糊记忆技术短板，并在认知智能技术领域，2022年累计获得了常识阅读理解挑战赛OpenBookQA等13项世界冠军；开源了6个大类、超过40个通用领域的系列中文预训练语言模型，相关模型库月均调用量超1000万，在Github平台获得星标数位列同类中文预训练语言模型第一并远超第二名。</p><p>　　数据积累：在严格遵守适用法律法规前提下，在多年认知智能系统研发推广中积累了超过50TB的行业语料和每天超10亿人次用户交互的活跃应用，为训练实现达到人类专家水平的行业认知大模型提供了海量行业文本语料和用户反馈数据，也为基于大模型的创新应用研发和试点推广提供了场景保障。</p><p>　　算力支撑：讯飞在总部自建有业界一流的数据中心，为大模型训练平台建设奠定了很好的硬件基石。此外，在工程技术方面实现了百亿参数大模型推理效率的近千倍加速，为未来更大更多认知智能大模型技术经济实惠规模化应用提供了可能。</p><p>　　结合我们多年来在深度学习算法、大模型技术、行业大数据、知识图谱、多模态感知、系统工程技术方面优势积累，通过最近两个多月的系统分析和快速验证，我们非常有信心实现ChatGPT类似的技术阶跃进步，并在中文认知智能领域达到国际领先水平。</p></div>', '2023-10-05 22:50:00');
INSERT INTO `articles` VALUES (235, '在ChatGPT时代，如何看待资本市场当前的热潮？', '<div class="left_zw"><p>　　不光是中国，全球资本都在ChatGPT的技术突破后看到了人工智能对社会产生的重大产业机遇，所以资本的热情被点燃也是非常自然的事情：一方面对于科大讯飞这种在认知智能领域有充分积累的企业和原来有做过大模型经验的公司都是利好；另一方面对于提供算力的厂商明确带来市场需求的增长空间；此外，ChatGPT刚出来前两周，就有500多个创业公司在各个领域围绕ChatGPT在各个行业开展了AIGC等模式的创业。众多行业都有被深度重构的机会，重构过程中一定会产生巨大的全新商业价值，因此资本市场的兴奋是可以理解的。</p><p>　　但是我们认为兴奋归兴奋，关键是要踏踏实实把科研做好，把产品做好，把服务做好，最终还是要回到我们提出来的人工智能红利能否兑现的三大标准：“有没有看得见摸得着的真实应用案例，有没有能够规模化推广应用的产品，有没有统计数据能够证明的应用成效”，只有这些才能够经得起时间的考验，才能够真正把人工智能做实做透。</p></div>', '2023-10-05 20:46:00');
INSERT INTO `articles` VALUES (236, 'ChatGPT爆火，开启AI竞争新赛道', '<div class="left_zw"><p>2023年，ChatGPT横空出世，迅速火遍全球。</p><p>作为人工智能领域的现象级应用，ChatGPT充满巨大的想象力和发展空间，微软、谷歌等科技巨头纷纷布局AIGC领域。</p><p>在技术演化的基础上，以自然语言处理为代表的人工智能算法有可能重构互联网和移动互联网的产品形态，促进教育业、医疗业、汽车业、金融业、消费业、媒体业、服务业和制造业等众多产业的升级，最终带来对应商业模式的变革。国内各地政府、各类大企业纷纷出手，相继布局ChatGPT类似模型。。英伟达CEO黄仁勋表示“ChatGPT 相当于 AI 界的 iPhone 问世”。现有ChatGPT的问世并非一蹴而就，在此之前，OpenAI已为ChatGPT基础模型的构建进行了长达5年的筹备与测试。2018年OpenAI发布了第一代GPT（Generative Pre-trained Transformer）模型，利用Transformer的神经网络架构，通过在大规模语料库上进行预训练来生成自然语言文本，从此自然语言处理进入“预训练”时代。在接下来的几年里，OpenAI不断改进和扩展GPT模型，推出了GPT-2、GPT-3等版本。这些模型在生成自然语言文本方面不断取得新的突破，并引起了学术界和产业界的高度关注。其中，GPT-3模型已含有1750亿超大规模的参数，并且能够借助语境学习（In-context Learning），判断语句的褒贬。比如，输入“我觉得你很有趣，这句话的情感是——”，那么GPT-3直接能够输出结果“褒义”，这标志着人工智能开始具备对文字情感的感知能力。</p></div>', '2023-11-27 05:39:00');
INSERT INTO `articles` VALUES (237, '最新！比尔·盖茨再谈ChatGPT：重要性不亚于互联网的发明，将改变我们的世界', '<div class="left_zw"> <p>　　中新网12月10日电 据科技媒体报道，微软和谷歌本周发布了一系列公告，正展开激烈竞争，力争在人工智能领域保持领先地位。</p> <div style="text-align:center"><img alt="" src="https://n.sinaimg.cn/spider20230211/329/w677h452/20230211/cfcb-c343e479bbc0f8c7cb885755d7df512c.png" style="border:0px solid #000000"/></div> <p>　　微软联合创始人比尔·盖茨（Bill Gates）近日表示，他对人工智能工具ChatGPT的未来充满信心。在2月10日接受德国《商业报纸》（Handelsblatt）采访时，他指出，“ChatGPT将改变我们的世界”。</p> <p>　　ChatGPT由微软投资的OpenAI于2022年12月推出，用户在短短两个月内增长至约1亿，成为历史上增长最快的应用程序之一。</p> <p>　　《每日经济新闻》记者注意到，盖茨在接受《福布斯》采访时也谈到了他与OpenAI的合作，称“OpenAI做得非常出色，他们在人工智能的多个领域处于领先地位，ChatGPT的广泛适用性也体现了这一点。”</p> <p>　　随着ChatGPT的火爆，许多职业人士感受到了压力。盖茨表示，随着机器人变得更加智能，人们需要关注那些从事体力劳动的蓝领工作。</p> <div class="adEditor"><div class="left_name right"> </div></div> </div>', '2024-11-05 09:31:00');
-- ----------------------------
-- Table structure for clicks
-- ----------------------------
DROP TABLE IF EXISTS `clicks`;
CREATE TABLE `clicks`  (
  `ClickID` int(11) NOT NULL AUTO_INCREMENT,
  `ContentID` int(11) NOT NULL,
  `ContentType` enum('Article','Video') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ClickCount` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ClickID`) USING BTREE,
  UNIQUE INDEX `ContentID_ContentType`(`ContentID` ASC, `ContentType` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of clicks
-- ----------------------------

-- ----------------------------
-- Table structure for personalinfo
-- ----------------------------
DROP TABLE IF EXISTS `personalinfo`;
CREATE TABLE `personalinfo`  (
  `Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `AvatarURL` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `GitHubAccount` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `WeChatID` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`Name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of personalinfo
-- ----------------------------
INSERT INTO `personalinfo` VALUES ('张昊星', '数据库设计', 'https://avatars.githubusercontent.com/u/112965322?v=4', '2113419@mail.nankai.edu.cn', 'fxingan', 'hbwyzjswy');
INSERT INTO `personalinfo` VALUES ('方奕', '后端部署', 'https://avatars.githubusercontent.com/u/127603540?v=4', '2112106@mail.nankai.edu.cn', 'chaojixiaogou', 'zhlhb17870364520');
INSERT INTO `personalinfo` VALUES ('梁晓储', '前端设计', 'https://avatars.githubusercontent.com/u/62194766?v=4', '2110951@mail.nankai.edu.cn', 'WangshuXC', 'WangshuXC');
INSERT INTO `personalinfo` VALUES ('王思宇', '爬虫编写', 'https://avatars.githubusercontent.com/u/101002810?v=4', '2112414@mail.nankai.edu.cn', 'thvarmy', 'W17710199820');
INSERT INTO `personalinfo` VALUES ('网站介绍', '<p>欢迎来到我们的人工智能介绍主页！我们致力于为您提供关于人工智能，特别是大模型的全面信息和科普知识，帮助您更好地了解这一领域的最新发展和应用。</p> <p>在我们的主页上，您将发现一个专门展示大模型相关内容的部分，涵盖了大量关于大模型的文章、视频和最新研究成果。我们通过这些内容，帮助用户深入理解大模型的原理、应用及其未来前景。</p> <p>除了文章和视频资源，我们还提供评论和点赞功能，方便您与其他用户进行互动交流，分享您的看法与意见。这一互动平台将促进大家共同探讨人工智能的影响力和潜力，推动更广泛的技术交流与学习。</p> <p>我们希望通过这个主页，为您提供一个集学习、分享和讨论为一体的人工智能平台，让您在探索大模型的同时，也能和全球的人工智能爱好者一起参与其中。</p> <p>感谢您访问我们的主页！如果您有任何问题或建议，欢迎随时与我们联系。我们期待与您共同探讨人工智能的无限可能。</p>', NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`UserID`) USING BTREE,
  UNIQUE INDEX `Username`(`Username` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', 'LFZW1234lfzw');

-- ----------------------------
-- Table structure for videocomments
-- ----------------------------
DROP TABLE IF EXISTS `videocomments`;
CREATE TABLE `videocomments`  (
  `CommentID` int(11) NOT NULL AUTO_INCREMENT,
  `VideoID` int(11) NOT NULL,
  `Comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `CommentDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`CommentID`) USING BTREE,
  INDEX `VideoID`(`VideoID` ASC) USING BTREE,
  INDEX `fk_video_comments_username`(`Username` ASC) USING BTREE,
  CONSTRAINT `fk_video_comments_username` FOREIGN KEY (`Username`) REFERENCES `users` (`username`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `video_comments_ibfk_2` FOREIGN KEY (`VideoID`) REFERENCES `videos` (`videoid`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of videocomments
-- ----------------------------

-- ----------------------------
-- Table structure for videolikes
-- ----------------------------
DROP TABLE IF EXISTS `videolikes`;
CREATE TABLE `videolikes`  (
  `LikeID` int(11) NOT NULL AUTO_INCREMENT,
  `VideoID` int(11) NOT NULL,
  `Likes` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`LikeID`) USING BTREE,
  INDEX `VideoID`(`VideoID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of videolikes
-- ----------------------------

-- ----------------------------
-- Table structure for videos
-- ----------------------------
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos`  (
  `VideoID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `PictureURL` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `UploadDate` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `VideoURL` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`VideoID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30013609 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of videos
-- ----------------------------
INSERT INTO `videos` VALUES (29571494, 'CCTV-13新闻频道', '[新闻直播间]半个月内两次宕机 ChatGPT安全性引担忧', 'https://p5.img.cctvpic.com/fmspic/2024/06/22/d7abc1407688448882e4d9aaa3643dc0-1.jpg', '2024-06-22 11:03', 'https://tv.cctv.com/2024/06/22/VIDEZT5Ium5FCXyYTvkVrLn7240622.shtml');
INSERT INTO `videos` VALUES (29571835, 'CCTV-4中文国际频道', '[国际时讯]半个月内两次宕机 ChatGPT安全性引担忧', 'https://p1.img.cctvpic.com/fmspic/2024/06/21/f2f8588608734674ba164d8af2e6b90f-1.jpg', '2024-06-21 23:03', 'https://tv.cctv.com/2024/06/21/VIDERl8xi0t0gbCSmr4UAHzL240621.shtml');
INSERT INTO `videos` VALUES (29572313, 'CCTV-4中文国际频道', '[正点财经]OpenAI放开限制 用户无需注册即可使用ChatGPT', 'https://p2.img.cctvpic.com/fmspic/2024/04/03/6f1fc1da016d49b88eb9c6c8d90fdfa8-1.jpg', '2024-04-03 10:03', 'https://tv.cctv.com/2024/04/03/VIDEihUGHHciYmAREkEIYHVE240403.shtml');
INSERT INTO `videos` VALUES (29579868, 'CCTV-13新闻频道', '[经济信息联播]OpenAI放开限制 用户无需注册即可使用ChatGPT', 'https://p4.img.cctvpic.com/fmspic/2024/04/02/a404c4c00f5448979dc36c9ba11c9eac-1.jpg', '2024-04-02 21:45', 'https://tv.cctv.com/2024/04/02/VIDEbASBMCZDsn6oxSKahEVr240402.shtml');
INSERT INTO `videos` VALUES (29583530, 'CCTV-1综合频道', '[正点财经]AI“吃电”凶猛 ChatGPT日耗电超50万千瓦时', 'https://p3.img.cctvpic.com/fmspic/2024/03/12/fa48bfe10a7341e8a30f6b805ba59103-1.jpg', '2024-03-12 10:29', 'https://tv.cctv.com/2024/03/12/VIDEdCICpvboupRoGc7Yklba240312.shtml');
INSERT INTO `videos` VALUES (29583809, 'CCTV-13新闻频道', '[经济信息联播]AI“吃电”凶猛 ChatGPT日耗电超50万千瓦时', 'https://p3.img.cctvpic.com/fmspic/2024/03/11/d722b6161a004d12ae1784b5bde710b0-1.jpg', '2024-03-11 22:29', 'https://tv.cctv.com/2024/03/11/VIDEbZzHE7RyJNpaaJ481sHZ240311.shtml');
INSERT INTO `videos` VALUES (29584370, 'CCTV-2财经频道', '[第一时间]OpenAI放开限制 用户无需注册即可使用ChatGPT', 'https://p5.img.cctvpic.com/fmspic/2024/04/03/9987666f197a44c3b5295fcd86ac11b1-1.jpg', '2024-04-03 07:51', 'https://tv.cctv.com/2024/04/03/VIDEl5zSvsNrwdgj2YVQTjtP240403.shtml');
INSERT INTO `videos` VALUES (29584776, 'CCTV-7国防军事频道', '[正点财经]AI“吃电”凶猛 ChatGPT日耗电超50万千瓦时', 'https://p3.img.cctvpic.com/fmspic/2024/03/12/18b6dffe74e04dd68f977469080cda59-1.jpg', '2024-03-12 11:23', 'https://tv.cctv.com/2024/03/12/VIDEZFPIvEEPWWY3ew94COSc240312.shtml');
INSERT INTO `videos` VALUES (29585263, 'CCTV-13新闻频道', '[新闻直播间]ChatGPT火出圈 ChatGPT存散播极端言论或假信息风险', 'https://p4.img.cctvpic.com/fmspic/2023/02/13/0c8b510f0af142a48d9a510ce37f2e9d-1.jpg', '2023-02-13 16:48', 'https://tv.cctv.com/2023/02/13/VIDE5hEmorRUChvVfJsc3NBB230213.shtml');
INSERT INTO `videos` VALUES (29586850, 'CCTV-13新闻频道', '[新闻直播间]ChatGPT火出圈 ChatGPT已在实际生活中大显身手', 'https://p1.img.cctvpic.com/fmspic/2024/03/12/524fb409c9124e3fa66f5a3ff25e954f-1.jpg', '2023-02-13 16:50', 'https://tv.cctv.com/2023/02/13/VIDEXXRS2mmZIUzUYcet9bbN230213.shtml');
INSERT INTO `videos` VALUES (29586988, 'CCTV-13新闻频道', '[共同关注]ChatGPT聊天机器人有何过人之处？', 'https://p3.img.cctvpic.com/fmspic/2023/02/13/e20dfbbcdcad4a30b40e35ad4943ac63-1.jpg', '2023-02-13 16:50', 'https://tv.cctv.com/2023/02/13/VIDEXXRS2mmZIUzUYcet9bbN230213.shtml');
INSERT INTO `videos` VALUES (29592013, 'CCTV-13新闻频道', '[共同关注]多数美国学生承认写作业用过ChatGPT', 'https://p1.img.cctvpic.com/fmspic/2023/02/13/cd852c2a78af4348a1b70f66c0ccedf7-1.jpg', '2023-02-13 18:50', 'https://tv.cctv.com/2023/02/13/VIDEmsxpb4W52HIiQeQJohrf230213.shtml');
INSERT INTO `videos` VALUES (29592335, 'CCTV-13新闻频道', '[共同关注]ChatGPT存散播极端言论或假信息风险', 'https://p3.img.cctvpic.com/fmspic/2023/02/13/6279f967a4fc4d40a041be9e09ba481f-1.jpg', '2023-02-13 18:58', 'https://tv.cctv.com/2023/02/13/VIDE2UxpJ2M3xEgqgqa3Z4TD230213.shtml');
INSERT INTO `videos` VALUES (29592409, 'CCTV-4中文国际频道', '[新闻直播间]五天内多次反转 ChatGPT之父突被免职后又闪电复职 ChatGPT火热 阿尔特曼却被踢出局', 'https://p2.img.cctvpic.com/fmspic/2023/11/27/9bee7bff428a4313a6ec1226e2b9babc-1.jpg', '2023-11-27 16:56', 'https://tv.cctv.com/2023/11/27/VIDEQsZyRVHQkfbz5tOFSrhA231127.shtml');
INSERT INTO `videos` VALUES (29607832, 'CCTV-13新闻频道', '[共同关注]ChatGPT：可以自主学习的AI聊天机器人', 'https://p5.img.cctvpic.com/fmspic/2023/02/13/90d97e6cd1f744ec808b8d1c30d22d6f-1.jpg', '2023-02-13 18:50', 'https://tv.cctv.com/2023/02/13/VIDEwnjTXkdqibuZyxdGMU08230213.shtml');
INSERT INTO `videos` VALUES (29614264, 'CCTV-13新闻频道', '[共同关注]ChatGPT聊天机器人有何过人之处？', 'https://p2.img.cctvpic.com/fmspic/2023/02/13/e9653b6ccb3a45a889d52e880ef2308d-1.jpg', '2023-02-13 18:54', 'https://tv.cctv.com/2023/02/13/VIDED1s7RbNwwmBc6G6COxOq230213.shtml');
INSERT INTO `videos` VALUES (29619859, 'CCTV-13新闻频道', '[共同关注]如何看待ChatGPT带来的争议和风险？', 'https://p4.img.cctvpic.com/fmspic/2023/07/20/bb65573a43ef4fe8ae48778bebd75c0f-1.jpg', '2023-02-13 18:58', 'https://tv.cctv.com/2023/02/13/VIDEE7FuDm4uSawOgVSWnG9C230213.shtml');

-- ----------------------------
-- Table structure for webviews
-- ----------------------------
DROP TABLE IF EXISTS `webviews`;
CREATE TABLE `webviews`  (
  `Views` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Views`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of webviews
-- ----------------------------
INSERT INTO `webviews` VALUES (0);

SET FOREIGN_KEY_CHECKS = 1;
