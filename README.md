## WorldPlayerLimiter

PocketMine-MP plugin that limits players in each world

## Category

PocketMine-MP plugins

## Requirements

PocketMine-MP Alpha_1.4 API 1.4.0

## Overview

**WorldPlayerLimiter** limits player for each world.

**EvolSoft Website:** http://www.evolsoft.tk

***This Plugin uses the New API. You can't install it on old versions of PocketMine.***

You can simply limit players for each world using command "/wpl addworld" and you can delete world limits simply using command "/wpl delworld"<br>
You can also place signs to teleport players between worlds. (read documentation)

**Commands:**

<dd><i><b>/worldplayerlimiter</b> - WorldPlayerLimiter commands (aliases: [wpl, wl, worldlimit])</i></dd>
<dd><i><b>/wpl addworld &lt;worldname&gt; &lt;limit&gt;</b> - Add world data (player limit)</i></dd>
<dd><i><b>/wpl delworld &lt;worldname&gt;</b> - Delete world data (player limit)</i></dd>
<dd><i><b>/wpl info</b> - Show info about this plugin</i></dd>
<dd><i><b>/wpl reload</b> - Reload the config</i></dd>
<dd><i><b>/wpl stats &lt;worldname&gt;</b> - Get world stats</i></dd>
<br>
**To-Do:**

<dd><i>- Bug fix (if bugs will be found)</i></dd>
<dd><i>- Fix signs bug</i></dd>
<dd><i>- Automatic sign update</i></dd>

## Documentation

**Sign Creation:**

*You must have the permission: "worldplayerlimiter.create-sign" to create signs and you must have the permission "worldplayerlimiter.use-sign" to use signs.*

<dd><i><b>Line 1:</b></i> [WPL]</dd>
<dd><i><b>Line 2:</b></i> &lt;worldname&gt;</dd>
<dd><i><b>Line 3 (optional):</b></i> custom sign name</dd>
<br>
*Output Examples:*
<br>
*Input:*
<dd><i><b>Line 1:</b></i> [WPL]</dd>
<dd><i><b>Line 2:</b></i> world</dd>
*Output:*
*if the target world has player limit, the output will be:*
<dd><i><b>Line 1:</b></i> world</dd>
<dd><i><b>Line 2:</b> current players/limit</i></dd>
<dd><i><b>Line 3: Click to join.</i></dd>
(Sign target: world)
else
Line 1: world
Line 2: Click to join.
Sign target: world

Input:
Line 1: [WPL]
Line 2: world
Line 3: custom
Output:
if the target world has player limit, the output will be:
Line 1: custom
Line 2: current players/limit
Line 3: Click to join.
Sign target: world
else
Line 1: custom
Line 2: Click to join.
Sign target: world
<br>
**Commands:**

<dd><i><b>/worldplayerlimiter</b> - WorldPlayerLimiter commands (aliases: [wpl, wl, worldlimit])</i></dd>
<dd><i><b>/wpl addworld &lt;worldname&gt; &lt;limit&gt;</b> - Add world data (player limit)</i></dd>
<dd><i><b>/wpl delworld &lt;worldname&gt;</b> - Delete world data (player limit)</i></dd>
<dd><i><b>/wpl info</b> - Show info about this plugin</i></dd>
<dd><i><b>/wpl reload</b> - Reload the config</i></dd>
<dd><i><b>/wpl stats &lt;worldname&gt;</b> - Get world stats</i></dd>
<br>
**Permissions:**
<br>
- <dd><i><b>worldplayerlimiter.*</b> - WorldPlayerLimiter permissions.</i></dd>
- <dd><i><b>worldplayerlimiter.use-sign</b> - Allows player to use WorldPlayerLimiter signs.</i></dd>
- <dd><i><b>worldplayerlimiter.create-sign</b> - Allows player to create WorldPlayerLimiter signs.</i></dd>
- <dd><i><b>worldplayerlimiter.commands.help</b> - WorldPlayerLimiter command Help permission.</i></dd>
- <dd><i><b>worldplayerlimiter.commands.info</b> - WorldPlayerLimiter command Info permission.</i></dd>
- <dd><i><b>worldplayerlimiter.commands.reload</b> - WorldPlayerLimiter command Reload permission.</i></dd>
- <dd><i><b>worldplayerlimiter.commands.stats</b> - WorldPlayerLimiter command Stats permission.</i></dd>
- <dd><i><b>worldplayerlimiter.commands.addworld</b> - WorldPlayerLimiter command Add World permission.</i></dd>
- <dd><i><b>worldplayerlimiter.commands.delworld</b> - WorldPlayerLimiter command Delete World permission.</i></dd>
